<?php

namespace App\Http\Controllers;
use App\Models\User;
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

    public function search()
    {
        $user = DB::table('users')
        ->join('follows','follows.follower_id','=','users.id')
        ->select('users.id as u_id','users.name','users.image','follows.follower_id as f_id')
        ->get();
        return view('user',compact('user'));
    }
}
