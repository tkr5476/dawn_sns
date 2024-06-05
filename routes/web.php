<?php
use App\Http\Controllers\TopController;
use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/top', [TopController::class, 'top'])->name('top');

