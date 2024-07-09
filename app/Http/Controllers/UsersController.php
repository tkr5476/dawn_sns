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

        // ユーザーが見つからない場合の処理
        if (!$userProfile) {
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



    public function updateProfile(Request $request)
    {
        // 1. データの受信/バリデーション
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'min:4', 'max:12', 'regex:/^[ぁ-んァ-ン一-龠a-zA-Z0-9\-_]+$/u'],
            'bio' => ['max:400'],
        ], [
            'name.required' => 'ユーザー名を入力してください。',
            'name.min' => 'ユーザー名は4文字以上12文字以内で入力してください。',
            'name.max' => 'ユーザー名は4文字以上12文字以内で入力してください。',
            'name.regex' => 'ユーザー名には全角漢字カタカナひらがな数字記号もしくは半角英数記号を入力してください。',
            'bio.max' => 'メッセージは400文字以内で入力してください。',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $data = $validator->validated();

        // 2. メールアドレスのバリデーション
        if ($request->email !== $user->email) {
            $emailValidator = Validator::make($request->all(), [
                'email' => ['required', 'max:255', 'email', 'regex:/^[a-zA-Z0-9\-_\.@]+$/', 'unique:users,email,' . $user->id],
            ], [
                'email.required' => 'メールアドレスを入力してください。',
                'email.max' => 'メールアドレスは255文字以内で入力してください。',
                'email.email' => 'メールアドレスは正しい形式で入力してください。',
                'email.regex' => 'メールアドレスには利用可能な文字を入力してください。',
                'email.unique' => '入力されたメールアドレスはすでに登録されています。別のメールアドレスを登録してください。',
            ]);

            if ($emailValidator->fails()) {
                return back()->withErrors($emailValidator)->withInput();
            }

            $data['email'] = $request->email;
        }

        // 3. パスワードのバリデーション（入力がある場合のみ）
        if ($request->filled('password')) {
            $passwordValidator = Validator::make($request->all(), [
                'password' => ['required', 'string', 'min:8', 'max:128', 'regex:/^[a-zA-Z0-9\-_.@+]*$/u'],
                'password_confirmation' => ['required', 'same:password'],
            ], [
                'password.required' => 'パスワードを入力してください。',
                'password.min' => 'パスワードは8文字以上128文字以内で入力してください。',
                'password.max' => 'パスワードは8文字以上128文字以内で入力してください。',
                'password.regex' => 'パスワードには半角英数記号を入力してください。',
                'password_confirmation.required' => '確認用のパスワードを入力してください。',
                'password_confirmation.same' => 'パスワードと確認の入力が一致しません。',
            ]);

            if ($passwordValidator->fails()) {
                return back()->withErrors($passwordValidator)->withInput();
            }

            $data['password'] = Hash::make($request->password);
        }

        // 4. 画像のバリデーションとアップロード
        if ($request->hasFile('image')) {
            $imageValidator = Validator::make($request->all(), [
                'image' => ['image', 'mimes:jpeg,png,bmp,gif,svg,webp', 'max:20480'],
            ], [
                'image.image' => '画像形式のファイルをアップロードしてください。',
                'image.mimes' => '画像形式のファイルをアップロードしてください。',
                'image.max' => 'アップロードするファイルは20MB以下にしてください。',
            ]);

            if ($imageValidator->fails()) {
                return back()->withErrors($imageValidator)->withInput();
            }

            // アップロードファイルの格納
            try {
                $fileName = $request->file('image')->getClientOriginalName();
                $Path = $request->file('image')->storeAs('/userIcon', $fileName);
                $data['image'] = $fileName;
            } catch (\Exception $e) {
                return back()->with('error', 'アイコン画像のアップロードに失敗しました。');
            }
        }

        // 5. DBのデータ更新
        try {
            $user->fill($data);
            $user->save();
        } catch (\Exception $e) {
            return back()->with('error', 'プロフィールの更新に失敗しました。');
        }

        // 6. セッションの更新
        Auth::setUser($user->fresh());


        return redirect('/loginUser');
    }
}
