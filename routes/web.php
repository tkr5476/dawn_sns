<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TopController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\FollowsController;
use App\Http\Controllers\FollowListsController;
use App\Http\Controllers\FollowerListsController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

    Route::controller(PostsController::class)->group(function () {
        Route::get('/top', 'index')->name('top');
        Route::post('/post/create', 'create')->name('post.create');
        Route::get('/post/{id}/editPost', 'editPost')->name('post.edit');
        Route::put('/post/update', 'update')->name('post.update');
        Route::delete('/post/delete', 'delete')->name('post.delete');
        Route::post('/posts/destroy', [PostsController::class, 'destroyPost'])->name('post.destroy');
    });

    Route::controller(UsersController::class)->group(function () {
        Route::get('/user/search', 'search')->name('user.search');
        Route::post('/user/search/again', 'again')->name('user.search.again');
        Route::get('/user/{id}/profile', 'userProfile')->name('user.profile');
        Route::get('/loginUser', 'loginUser')->name('loginUser');
        Route::get('/editUserProfile', 'editUserProfile')->name('profile.edit');
        Route::put('/userProfile/update', 'updateProfile')->name('profile.update');
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
