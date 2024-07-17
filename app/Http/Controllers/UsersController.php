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
    public function userProfile(Request $request)
    {
        $this->userProfileValidator($request->all());

        $id = $request->id;

        $userProfile = DB::table('users')
            ->where('id', $id)//選択されたユーザーのIDと同じかどうか
            ->select('id', 'name', 'image', 'bio')
            ->first();

        // ユーザーが見つからない場合の処理
        if (!$userProfile) {
            return view('/user.userProfile', ['userProfile' => null]);
        }

        $userPosts = DB::table('posts')
            ->where('user_id', $id)//選択されたユーザーのIDと同じかどうか
            ->select('id', 'user_id', 'post', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        $followings = DB::table('follows')//フォローしているか判別するため
            ->where('follower_id', Auth::id())
            ->get();


        return view('/user.userProfile', ['userProfile' => $userProfile, 'userPosts' => $userPosts, 'followings' => $followings]);
    }


    public function loginUserProfileValidator(array $data)
    {
        //謎のバリデーション（おそらく不要）
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
    public function loginUserProfile()
    {
        $loginUser = DB::table('users')
            ->where('id', Auth::id())//ログインユーザーのIDと同じものを取得
            ->select('id', 'name', 'image', 'email', 'bio')
            ->first();

        $loginUserPosts = DB::table('posts')
            ->where('user_id', Auth::id())//ログインユーザーのIDと同じものを取得
            ->select('id', 'user_id', 'post', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('/user.loginUserProfile', ['loginUser' => $loginUser, 'loginUserPosts' => $loginUserPosts]);

        if (!$loginUser) {
            return view('/user.loginUserProfile', ['loginUser' => null]);
        }
    }


    public function editUserProfile()
    {

        $user = DB::table('users')
            ->where('id', Auth::id())//ログインユーザーのIDと同じものを取得
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
        //バリデーションが失敗かどうか（失敗の時はtrueが返される）,
        //失敗の時はエラーメッセージを表示するための処理と「withInput();」でユーザーが入力した値を保持するための処理をする

        $data = $validator->validated();//バリデーションが成功したらバリデーションした「name,bio」を$dataに格納
        //ここからまた別の処理
        $user = Auth::user();//ログインユーザーのモデルを取得

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
            ]);//usersテーブルのメールアドレスと違う値が入力された時

            if ($emailValidator->fails()) {
                return back()->withErrors($emailValidator)->withInput();
            }

            $data['email'] = $request->email;//バリデーションが成功したらメールアドレスを$dataに格納
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
            ]);//リクエストパラメータ（パスワード）が存在し、かつ空でない（空文字列でない）場合にバリデーションを実施

            if ($passwordValidator->fails()) {
                return back()->withErrors($passwordValidator)->withInput();
            }

            $data['password'] = Hash::make($request->password);//バリデーションが成功したらパスワードをハッシュ化して$dataに格納
        }

        // 4. 画像のバリデーションとアップロード
        if ($request->hasFile('image')) {
            $imageValidator = Validator::make($request->all(), [
                'image' => ['image', 'mimes:jpeg,png,bmp,gif,svg,webp', 'max:20480'],
            ], [
                'image.image' => '画像形式のファイルをアップロードしてください。',
                'image.mimes' => '画像形式のファイルをアップロードしてください。',
                'image.max' => 'アップロードするファイルは20MB以下にしてください。',
            ]);//リクエストパラメータ（画像）が存在する場合にバリデーションを実施

            if ($imageValidator->fails()) {
                return back()->withErrors($imageValidator)->withInput();
            }

            // アップロードファイルの格納
            try {
                $fileName = $request->file('image')->getClientOriginalName();//アップロードされたファイルの元々のファイル名を取得します。
                $Path = $request->file('image')->storeAs('/userIcon', $fileName);//アップロードされたファイルを指定したパス（'/userIcon'）に指定した名前（$fileName）で保存します。
                $data['image'] = $fileName;//バリデーションが成功したらファイル名を$dataに格納
            } catch (\Exception $e) {
                return back()->with('error', 'アイコン画像のアップロードに失敗しました。');
            }
        }

        // 5. DBのデータ更新
        try {
            $user->fill($data);
            //バリデーション済みのデータでユーザーモデルを更新
            //fill -> $fillableプロパティで指定された属性を一括で更新する
            $user->save();//ユーザーモデルをデータベースに保存
        } catch (\Exception $e) {
            return back()->with('error', 'プロフィールの更新に失敗しました。');
        }

        // 6. セッションの更新
        Auth::setUser($user->fresh());//最新の状態を保持するための処理


        return redirect()->route('loginUser.profile');
    }
}
