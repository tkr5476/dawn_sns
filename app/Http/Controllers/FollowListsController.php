<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class FollowListsController extends Controller
{

    public function followList()
{
    $followIcons = DB::table('follows')
        ->join('users', 'follows.user_id', '=', 'users.id')
        ->where('follows.follower_id', Auth::id())
        ->select('users.id', 'users.name', 'users.image')
        ->orderBy('users.name', 'desc')
        ->get();

    $followPosts = DB::table('follows')
        ->join('users', 'follows.user_id', '=', 'users.id')
        ->leftJoin('posts', 'follows.user_id', '=', 'posts.user_id')
        ->where('follows.follower_id', Auth::id())
        ->select('users.id', 'users.name', 'users.image', 'posts.post', 'posts.created_at')
        ->orderBy('posts.created_at', 'desc')
        ->get();

    return view('followList', ['followIcons' => $followIcons, 'followPosts' => $followPosts]);
}

public function userProfile($id)
{
    $userProfile = DB::table('users')
        ->where('id', $id)
        ->first();

    return view('userProfile', ['userProfile' => $userProfile]);
}
    // public function followList()
    // {
    //     $followLists = [
    //         DB::table('follows')
    //             ->join('users', 'follows.user_id', '=', 'users.id')
    //             ->where('follower_id', Auth::id())
    //             ->select('users.id as u_id', 'users.name', 'users.image')
    //             ->orderBy('users.name', 'desc')
    //             ->get(),

    //         DB::table('follows')
    //             ->join('posts', 'follows.user_id', '=', 'posts.user_id')
    //             ->where('follower_id', Auth::id())
    //             ->select('posts.post', 'posts.created_at')
    //             ->orderBy('posts.created_at', 'desc')
    //             ->get()
    //     ];

    //     return view('followList', ['followLists' => $followLists]);
    // }
}
