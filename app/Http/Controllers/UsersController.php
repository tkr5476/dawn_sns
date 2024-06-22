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
    // public function search(Request $request)
    // {
    //     $user = User::where('name',$request->name)->get('name');
    //     return view('user',compact('user'));
    // }
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
        ->where('id','!=',Auth::id())
        ->select('id','name','image')
        ->get();

        $followings = DB::table('follows')
        ->where('follower_id',Auth::id())
        ->get();

        return view('user',['users' => $users, 'followings'=> $followings]);
    }

    public function again(Request $request)
    {
        $this->validator($request->all());
        $keyword = $request->name;

        $users = DB::table('users')
        ->where('id','!=',Auth::id())
        ->where('name', 'like', '%' . $keyword . '%')
        ->select('id','name','image')
        ->get();

        $followings = DB::table('follows')
        ->where('follower_id',Auth::id())
        ->get();


        return view('user',['users' => $users, 'followings'=> $followings, 'keyword' => $keyword ]);
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


        return view('userProfile', ['userProfile' => $userProfile, 'userPosts' => $userPosts]);
    }

}
