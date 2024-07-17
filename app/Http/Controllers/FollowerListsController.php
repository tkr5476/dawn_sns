<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class FollowerListsController extends Controller
{
    public function followerList()
    {
        //ユーザーアイコンを取得
        $followerUsers = DB::table('follows')
            ->join('users', 'follows.follower_id', '=', 'users.id')
            ->where('follows.user_id', Auth::id())//フォロワーユーザーかどうか
            ->select('users.id', 'users.name', 'users.image')
            ->orderBy('users.name', 'desc')
            ->get();

        //フォロワーーユーザーの投稿を取得
        $followerPosts = DB::table('follows')
            ->join('users', 'follows.follower_id', '=', 'users.id')
            ->join('posts', 'follows.user_id', '=', 'posts.user_id')
            ->where('follows.user_id', Auth::id())//フォロワーユーザーかどうか
            ->select('users.id', 'users.name', 'users.image', 'posts.post', 'posts.created_at')
            ->orderBy('posts.created_at', 'desc')
            ->get();

        return view('/user/followerList', ['followerUsers' => $followerUsers, 'followerPosts' => $followerPosts]);
    }
}


