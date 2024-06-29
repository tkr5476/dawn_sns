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

    protected function postValidator(array $data)
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

    public function index()
    {
        $posts = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->join('follows', 'posts.user_id', '=', 'follows.user_id')
            ->where('users.id', Auth::id())
            ->orWhere('follows.follower_id', Auth::id())
            ->select('users.id as u_id', 'users.name', 'users.image', 'posts.id as p_id', 'posts.post', 'posts.created_at')
            ->orderBy('posts.created_at', 'desc')
            ->get();

        return view('top.top', ['posts' => $posts]);
    }

    public function create(Request $request)
    {
        $this->postValidator($request->all())->validate();

        $post = $request->input('post');
        DB::table('posts')->insert([
            'post' => $post,
            'user_id' => Auth::id(),
            'created_at' => now(),
        ]);

        return redirect('/top');
    }

    public function editValidator(array $data)
    {
        return Validator::make(
            $data,
            [
                'post.id' => ['required', 'regex:/^[0-9]+$/']
            ],
            [
                'post.required' => 'メッセージ情報を取得できませんでした。もう一度やり直してください。',
                'post.regex' => 'メッセージ情報を取得できませんでした。もう一度やり直してください。',
            ]
        );
    }
    public function editForm(Request $request)
    {
        $this->editValidator($request->all())->validate();

        $id = $request->input('id');
        $post = DB::table('posts')
            ->where('id', $id)
            ->first();

        return view('top.update', ['post' => $post]);
    }

    public function updateValidator(array $data)
    {
        return Validator::make(
            $data,
            [
                'post.id' => ['required', 'regex:/^[0-9]+$/'],
                'post.post' => ['required', 'string', 'max:400']
            ],
            [
                'post.id.required' => 'メッセージの編集に失敗しました。もう一度やり直してください。',
                'post.id.regex' => 'メッセージの編集に失敗しました。もう一度やり直してください。',
                'post.post.required' => 'メッセージを入力してください。',
                'post.post.max' => 'メッセージは400文字以内で入力してください。',
            ]
        );
    }
    public function update(Request $request)
    {
        $this->updateValidator($request->all())->validate();

        $id = $request->input('id');
        $post = $request->input('post');
        DB::table('posts')
            ->where('id', $id)
            ->update(['post' => $post]);

        return redirect('/top');
    }


    public function deleteValidator(array $data)
    {
        return Validator::make(
            $data,
            [
                'post.id' => ['required', 'regex:/^[0-9]+$/']
            ],
            [
                'post.required' => 'メッセージの削除に失敗しました。もう一度やり直してください。',
                'post.regex' => 'メッセージの削除に失敗しました。もう一度やり直してください。',
            ]
        );
    }

    public function delete(Request $request)
    {
        $this->deleteValidator($request->all())->validate();

        $id = $request->input('id');
        DB::table('posts')
            ->where('id', $id)
            ->delete();

        return redirect('/top');
    }
}
