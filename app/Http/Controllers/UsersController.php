<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


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

        if (!$user) {
            return redirect('/loginUser')->with('error', 'ユーザー情報の取得に失敗しました。もう一度やり直してください。');
        }

        return view('user.editUserProfile', compact('user'));
    }
    public function updateProfile(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $email = $request->input('email');
        $bio = $request->input('bio');
        $password = $request->input('password');
        $image = $request->input('image');

        DB::table('users')
            ->where('id', $id)
            ->update([
                'name' => $name,
                'email' => $email,
                'bio' => $bio,
                'password' => $password,
                'image' => $image,
            ]);


        return redirect('/loginUser');
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
}
