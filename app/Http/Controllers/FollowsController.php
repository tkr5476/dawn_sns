<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Follow;

class FollowsController extends Controller
{

    public function add(Request $request)
    {
        $follow = $request->input('id');

        DB::table('follows')->insert([
            'follower_id' => Auth::id(),
            'user_id' => $follow,
        ]);

        return redirect('/searchUser');
    }

    public function delete(Request $request)
    {
        $follow = $request->input('id');

        DB::table('follows')
            ->where([
                'follower_id' => Auth::id(),
                'user_id' => $follow,
            ])
            ->delete();

        return redirect('/searchUser');
    }
}
