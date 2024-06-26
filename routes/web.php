<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TopController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::controller(PostsController::class)->group(function () {
    Route::get('/top', 'index')->name('top');
    Route::post('/post/create', 'create')->name('post.create');
    Route::get('/post/{p_id}/update-form', 'editForm')->name('post.edit');
    Route::put('/post/update', 'update')->name('post.update');
    Route::delete('/post/delete', 'delete')->name('post.delete');
});

Route::controller(UsersController::class)->group(function () {
    Route::get('/user/search', 'search')->name('user.search');
    Route::post('/user/search/again', 'again')->name('user.search.again');
    Route::get('/user/{targetUserId}/profile', 'userProfile')->name('user.profile');
    Route::get('/loginUser', 'loginUser')->name('loginUser');
    Route::get('/editUserProfile', 'editUserProfile')->name('editUserProfile');
    Route::put('/userProfile/update', 'updateProfile')->name('updateProfile');
});

Route::controller(FollowsController::class)->group(function () {
    Route::post('/user/follow/add', 'add')->name('user.follow.add');
    Route::post('/user/follow/delete', 'delete')->name('user.follow.delete');
});

Route::controller(FollowListsController::class)->group(function () {
    Route::get('/followList/index', 'followList')->name('user.follows');
});

Route::controller(FollowerListsController::class)->group(function () {
    Route::get('/followerList/index', 'followerList')->name('user.followers');
});
