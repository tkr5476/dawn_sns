<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;



class FollowListsController extends Controller
{

    public function followList()
    {
        //ユーザーアイコンを取得
        $followUsers = DB::table('follows')
            ->join('users', 'follows.user_id', '=', 'users.id')
            ->where('follows.follower_id', Auth::id())//フォローユーザーかどうか
            ->select('users.id', 'users.name', 'users.image')
            ->orderBy('users.name', 'desc')
            ->get();

            //フォローユーザーの投稿を取得
        $followPosts = DB::table('follows')
            ->join('users', 'follows.user_id', '=', 'users.id')
            ->Join('posts', 'follows.user_id', '=', 'posts.user_id')
            ->where('follows.follower_id', Auth::id())//フォローユーザーかどうか
            ->select('users.id', 'users.name', 'users.image', 'posts.post', 'posts.created_at')
            ->orderBy('posts.created_at', 'desc')
            ->get();

        return view('/user/followList', ['followUsers' => $followUsers, 'followPosts' => $followPosts]);
    }
}
