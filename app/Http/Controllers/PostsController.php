<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'post' => ['required', 'string', 'max:400']
            ],
            [
                'post.required' => 'メッセージを入力してください。',
                'post.max' => 'メッセージは400文字以内で入力してください。',
            ]
        );
    }


    public function create(Request $request)
    {
        $this->validator($request->all())->validate();
        $post = $request->input('post');
        DB::table('posts')->insert([
            'post' => $post,
            'user_id' => Auth::id(),
            'created_at' => now(),
        ]);

        return redirect('/top');
    }

    public function index()
    {
        $posts = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select('users.id as u_id', 'users.name', 'users.image', 'posts.id as p_id', 'posts.post', 'posts.created_at')
            ->get();

        return view('top.top', ['posts' => $posts]);
    }

    public function editForm($id)
    {
        $post = DB::table('posts')
            ->where('id', $id)
            ->first();

        return view('top.update', ['post' => $post]);
    }

    public function update(Request $request)
    {
        $this->validator($request->all())->validate();
        $id = $request->input('id');
        $post = $request->input('post');
        DB::table('posts')
            ->where('id', $id)
            ->update(['post' => $post]);

        return redirect('/top');
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        DB::table('posts')
            ->where('id', $id)
            ->delete();

        return redirect('/top');
    }
}
