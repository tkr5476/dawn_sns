<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        view()->composer('*', function ($view) {

            $followCounts = DB::table('follows')
                ->where('follower_id', Auth::id())
                ->count();


            $followerCounts = DB::table('follows')
                ->where('user_id', Auth::id())
                ->count();


            view()->share(['followCounts' => $followCounts, 'followerCounts' => $followerCounts]);
        });
    }
}
