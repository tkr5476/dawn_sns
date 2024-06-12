<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TopController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostsController;





Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('top', [PostsController::class,'index'])->name('top');
Route::post('/post/create',[PostsController::class,'create'])->name('post.create');

Route::get('/post/{p_id}/update-form',[PostsController::class,'editForm'])->name('post.edit');
Route::put('/post/update',[PostsController::class,'update'])->name('post.update');
Route::delete('/post/delete',[PostsController::class,'delete'])->name('post.delete');
Route::get('/user',[UsersController::class,'index'])->name('user.index');
Route::get('/user/search',[UsersController::class,'search'])->name('user.search');
Route::post('/user/search/1',[UsersController::class,'search'])->name('user.search.match');
