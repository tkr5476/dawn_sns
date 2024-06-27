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

    public function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'user' => ['regex:/^[ぁ-んァ-ン一-龠a-zA-Z0-9\-_]+$/u']
            ],
            [
                'user.regex' => 'ユーザー名には全角漢字カタカナひらがな数字記号もしくは半角英数記号を入力してください。',
            ]
        );
    }

    // }
    public function search(Request $request)
    {
        $this->validator($request->all());

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
        $this->validator($request->all());
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

    public function userProfile($targetUserId)
    {
        $userProfile = DB::table('users')
            ->where('users.id', $targetUserId)
            ->select('users.id', 'users.name', 'users.image', 'users.bio')
            ->first();

        $userPosts = DB::table('posts')
            ->where('user_id', $targetUserId)
            ->select('posts.id', 'posts.user_id', 'posts.post', 'posts.created_at')
            ->orderBy('posts.created_at', 'desc')
            ->get();

        $followings = DB::table('follows')
            ->where('follower_id', Auth::id())
            ->get();

        return view('user.userProfile', ['userProfile' => $userProfile, 'userPosts' => $userPosts, 'followings' => $followings]);
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
    }

    public function editUserProfile()
    {
        $user = DB::table('users')
            ->where('id', Auth::id())
            ->select('id', 'name', 'email', 'bio', 'password', 'image')
            ->first();


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
}
