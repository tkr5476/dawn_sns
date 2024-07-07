<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{

    public function searchValidator(array $data)
    {
        return Validator::make(
            $data,
            [
                'name' => ['regex:/^[ぁ-んァ-ン一-龠a-zA-Z0-9\-_]+$/u']
            ],
            [
                'name.regex' => 'ユーザー名には全角漢字カタカナひらがな数字記号もしくは半角英数記号を入力してください。',
            ]
        );
    }
    public function search(Request $request)
    {
        $this->searchValidator($request->all());

        $users = DB::table('users')
            ->where('id', '!=', Auth::id())
            ->select('id', 'name', 'image')
            ->get();

        $followings = DB::table('follows')
            ->where('follower_id', Auth::id())
            ->get();

        return view('user.searchUser', ['users' => $users, 'followings' => $followings]);
    }


    public function again(Request $request)
    {
        $this->searchValidator($request->all());
        $keyword = $request->name;

        $users = DB::table('users')
            ->where('id', '!=', Auth::id())
            ->where('name', 'like', '%' . $keyword . '%')
            ->select('id', 'name', 'image')
            ->get();

        $followings = DB::table('follows')
            ->where('follower_id', Auth::id())
            ->get();


        return view('user.searchUser', ['users' => $users, 'followings' => $followings, 'keyword' => $keyword]);
    }

//     この修正では以下の点に対応しています：
// データの受信とバリデーションを分離しました。
// メールアドレスの変更がある場合のみ、追加のバリデーションを行います。
// 3. パスワードの入力がある場合のみ、バリデーションとハッシュ化を行います。
// 4. 画像のアップロードがある場合のみ、バリデーションとアップロード処理を行います。
// 5. DBの更新処理をtry-catchで囲み、エラーハンドリングを追加しました。
// 6. セッションの更新を追加しました。
// また、バリデーションルールとエラーメッセージはupdateProfileValidatorメソッド内で定義されていると仮定しています。必要に応じて、そのメソッド内でエラーメッセージをカスタマイズできます。

    public function userProfileValidator(array $data)
    {
        return Validator::make(
            $data,
            [
                'id' => ['required', 'regex:/^[0-9]+$/']
            ],
            [
                'id.required' => 'ユーザー情報の取得に失敗しました。もう一度やり直してください。',
                'id.regex' => 'ユーザー情報の取得に失敗しました。もう一度やり直してください。',
            ]
        );
    }
    public function userProfile($id)
    {
        $userProfile = DB::table('users')
            ->where('id', $id)
            ->select('id', 'name', 'image', 'bio')
            ->first();

        if (!$userProfile) {
            // ユーザーが見つからない場合の処理
            return view('user.userProfile', ['userProfile' => null]);
        }

        $userPosts = DB::table('posts')
            ->where('user_id', $id)
            ->select('id', 'user_id', 'post', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        $followings = DB::table('follows')
            ->where('follower_id', Auth::id())
            ->get();


        return view('user.userProfile', ['userProfile' => $userProfile, 'userPosts' => $userPosts, 'followings' => $followings]);
    }



    public function loginUserProfileValidator(array $data)
    {
        return Validator::make(
            $data,
            [
                'name' => ['required', 'min:4', 'max:12', 'regex:/^[ぁ-んァ-ン一-龠a-zA-Z0-9\-_]+$/u'],
                'bio' => ['max:400']
            ],
            [
                'name.required' => 'ユーザー名を入力してください。',
                'name.min' => 'ユーザー名は4文字以上12文字以内で入力してください。',
                'name.max' => 'ユーザー名は4文字以上12文字以内で入力してください。',
                'name.regex' => 'ユーザー名には全角漢字カタカナひらがな数字記号もしくは半角英数記号を入力してください。',
                'bio.max' => 'メッセージは400文字以内で入力してください。',
            ]
        );
    }
    public function loginUser()
    {
        $loginUser = DB::table('users')
            ->where('id', Auth::id())
            ->select('id', 'name', 'image', 'email', 'bio')
            ->first();

        $loginUserPosts = DB::table('posts')
            ->where('user_id', Auth::id())
            ->select('id', 'user_id', 'post', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.loginUser', ['loginUser' => $loginUser, 'loginUserPosts' => $loginUserPosts]);

        if (!$loginUser) {
            return view('user.loginUser', ['loginUser' => null]);
        }
    }


    public function editUserProfile()
    {

        $user = DB::table('users')
            ->where('id', Auth::id())
            ->select('id', 'name', 'email', 'bio', 'password', 'image')
            ->first();

        return view('user.editUserProfile', compact('user'));

        if (!$user) {
            return redirect('/loginUser')->with('error', 'ユーザー情報の取得に失敗しました。もう一度やり直してください。');
        }
    }

    public function updateProfileValidator(array $data)
    {
        return Validator::make(
            $data,
            [
                'name' => ['required', 'min:4', 'max:12', 'regex:/^[ぁ-んァ-ン一-龠a-zA-Z0-9\-_]+$/u'],
                'bio' => ['max:400'],
                'email' => ['required', 'max:255', 'email', 'regex:/^[a-zA-Z0-9\-_\.@]+$/', Rule::unique('users')->ignore(Auth::id())],
                'password' => ['required', 'string', 'min:8', 'max:128', 'regex:/^[a-zA-Z0-9\-_.@+]*$/u'],
                'password_confirmation' => ['required', 'same:password'],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,bmp,gif,svg,webp', 'max:20480'],
                'id' => ['required', 'regex:/^[0-9]+$/']
            ],
            [
                'name.required' => 'ユーザー名を入力してください。',
                'name.min' => 'ユーザー名は4文字以上12文字以内で入力してください。',
                'name.max' => 'ユーザー名は4文字以上12文字以内で入力してください。',
                'name.regex' => 'ユーザー名には全角漢字カタカナひらがな数字記号もしくは半角英数記号を入力してください。',
                'bio.max' => 'メッセージは400文字以内で入力してください。',
                'email.required' => 'メールアドレスを入力してください。',
                'email.max' => 'メールアドレスは255文字以内で入力してください。',
                'email.email' => 'メールアドレスは正しい形式で入力してください。',
                'email.regex' => 'メールアドレスには利用可能な文字を入力してください。',
                'email.unique' => '入力されたメールアドレスはすでに登録されています。別のメールアドレスを登録してください。',
                'password.required' => 'パスワードを入力してください。',
                'password.min' => 'パスワードは8文字以上128文字以内で入力してください。',
                'password.max' => 'パスワードは8文字以上128文字以内で入力してください。',
                'password.regex' => 'パスワードには半角英数記号を入力してください。',
                'password_confirmation.required' => '確認用のパスワードを入力してください。',
                'password_confirmation.same' => 'パスワードと確認の入力が一致しません。',
                'image.image' => '画像形式のファイルをアップロードしてください。',
                'image.mimes' => '画像形式のファイルをアップロードしてください。',
                'image.max' => 'アップロードするファイルは20MB以下にしてください。',
            ]
        );
    }

    public function updateProfile(Request $request)
    {
        // 1. データの受信受付
        // 2. バリデーション
        $validated = $this->updateProfileValidator($request->all())->validate();

        // 3. メールアドレスの変更チェック
        $user = Auth::user();
        if ($validated['email'] !== $user->email) {
            $validated['email'] = $this->validateEmail($validated['email']);
        }

        // 4. パスワードのバリデーション（入力がある場合のみ）
        if ($request->filled('password')) {
            $this->validatePassword($request);
            $validated['password'] = Hash::make($validated['password']);
        }

        // 5. 画像のバリデーションとアップロード
        if ($request->hasFile('image')) {
            $validated['image'] = $this->validateAndUploadImage($request);
        }

        // 6. DBのデータ更新
        try {
            $user->update($validated);
        } catch (\Exception $e) {
            return back()->with('error', 'プロフィールの更新に失敗しました。');
        }

        // 7. セッションの更新
        Auth::setUser($user->fresh());

        return redirect('/loginUser')->with('success', 'プロフィールが更新されました。');
    }

    private function validateEmail($email)
    {
        // メールアドレスの追加バリデーション
        return $email;
    }

    private function validatePassword($request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'max:128', 'regex:/^[a-zA-Z0-9\-_.@+]*$/u'],
            'password_confirmation' => ['required', 'same:password'],
        ]);
    }

    private function validateAndUploadImage($request)
    {
        $request->validate([
            'image' => ['image', 'mimes:jpeg,png,bmp,gif,svg,webp', 'max:20480'],
        ]);

        $path = $request->file('image')->store('public/userIcon');
        return Storage::url($path);
    }


    //1回目の修正
//     public function updateProfile(Request $request)
// {
//     $user = Auth::user();
//     $data = [];
//     $rules = [];
//     $messages = []; // エラーメッセージはこちらで定義します

//     // 1. nameとbioのバリデーション
//     $rules['name'] = ['required', 'min:4', 'max:12', 'regex:/^[ぁ-んァ-ン一-龠a-zA-Z0-9\-_]+$/u'];
//     $rules['bio'] = ['max:400'];

//     // 2. emailのバリデーション（変更がある場合のみ）
//     if ($request->email !== $user->email) {
//         $rules['email'] = ['required', 'max:255', 'email', 'regex:/^[a-zA-Z0-9\-_\.@]+$/', Rule::unique('users')->ignore($user->id)];
//     }

//     // 3. passwordのバリデーション（入力がある場合のみ）
//     if ($request->filled('password')) {
//         $rules['password'] = ['required', 'string', 'min:8', 'max:128', 'regex:/^[a-zA-Z0-9\-_.@+]*$/u'];
//         $rules['password_confirmation'] = ['required', 'same:password'];
//     }

//     // 4. imageのバリデーション（アップロードがある場合のみ）
//     if ($request->hasFile('image')) {
//         $rules['image'] = ['image', 'mimes:jpeg,png,bmp,gif,svg,webp', 'max:20480'];
//     }

//     // バリデーション実行
//     $validated = $request->validate($rules, $messages);

//     // データの準備
//     $data['name'] = $validated['name'];
//     $data['bio'] = $validated['bio'];
//     if (isset($validated['email'])) {
//         $data['email'] = $validated['email'];
//     }
//     if (isset($validated['password'])) {
//         $data['password'] = Hash::make($validated['password']);
//     }

//     // 5. 画像のアップロード処理
//     if ($request->hasFile('image')) {
//         $path = $request->file('image')->store('public/userIcon');
//         $data['image'] = str_replace('public/', '', $path);
//     }

//     // 6. DBの更新
//     try {
//         $user->update($data);
//     } catch (\Exception $e) {
//         return back()->with('error', 'プロフィールの更新に失敗しました。'); // VE-010-001に対応するメッセージ
//     }

//     // 7. セッションの更新（必要に応じて）
//     Auth::setUser($user->fresh());

//     return redirect('/loginUser')->with('success', 'プロフィールが更新されました。');
// }

    //条件分岐なしバージョン
    // public function updateProfile(Request $request)
    // {
    //     $validated = $this->updateProfileValidator($request->all())->validate();

    //     $data = [
    //         'name' => $validated['name'],
    //         'email' => $validated['email'],
    //         'bio' => $validated['bio'],
    //         'updated_at' => now(),
    //     ];

    //     if ($request->filled('password')) {
    //         $data['password'] = Hash::make($validated['password']);
    //     }

    //     if ($request->hasFile('image')) {
    //         $path = $request->file('image')->store('/storage/app/public/userIcon/');
    //         $data['image'] = $path;
    //     }

    //     DB::table('users')
    //         ->where('id', Auth::id())
    //         ->update($data);

    //     return redirect('/loginUser')->with('success', 'プロフィールが更新されました。');
    // }


    //自分で作成
    // public function updateProfile(Request $request)
    // {
    //     $this->updateProfileValidator($request->all());

    //     $id = $request->input('id');
    //     $name = $request->input('name');
    //     $email = $request->input('email');
    //     $bio = $request->input('bio');
    //     $password = $request->input('password');
    //     $image = $request->input('image');

    //     DB::table('users')
    //         ->where('id', Auth::id())
    //         ->update([
    //             'name' => $name,
    //             'email' => $email,
    //             'bio' => $bio,
    //             'password' => $password,
    //             'image' => $image,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);


    //     return redirect('/loginUser');
    // }

    //バリデーションとメソッド

}
