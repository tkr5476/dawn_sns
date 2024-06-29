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

    public function addValidator(array $data)
    {
        return Validator::make(
            $data,
            [
                'id' => ['required', 'regex:/^[0-9]+$/']
            ],
            [
                'id.required' => 'フォローに失敗しました。もう一度やり直してください。',
                'id.regex' => 'フォローに失敗しました。もう一度やり直してください。',
            ]
        );
    }

    public function add(Request $request)
    {
        $this->addValidator($request->all())->validate();

        $follow = $request->input('id');
        DB::table('follows')->insert([
            'follower_id' => Auth::id(),
            'user_id' => $follow,
        ]);

        return redirect()->route('user.search');
    }

    public function deleteValidator(array $data)
    {
        return Validator::make(
            $data,
            [
                'id' => ['required', 'regex:/^[0-9]+$/']
            ],
            [
                'id.required' => 'フォロー解除に失敗しました。もう一度やり直してください。',
                'id.regex' => 'フォロー解除に失敗しました。もう一度やり直してください。',
            ]
        );
    }
    public function delete(Request $request)
    {
        $this->deleteValidator($request->all())->validate();

        $follow = $request->input('id');
        DB::table('follows')
            ->where([
                'follower_id' => Auth::id(),
                'user_id' => $follow,
            ])
            ->delete();

            return redirect()->route('user.search');
        }
}
