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

    public function index()
    {
        $followUser = DB::table('follows')
            ->where('follower_id', Auth::id())
            ->pluck('user_id');

        $followUser->push(Auth::id());

        $posts = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->whereIn('posts.user_id', $followUser)
            ->select('users.id as u_id', 'users.name', 'users.image', 'posts.id as p_id', 'posts.post', 'posts.created_at')
            ->orderBy('posts.created_at', 'desc')
            ->get();

        return view('top.top', ['posts' => $posts]);
    }


    protected function createValidator(array $data)
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
        $this->createValidator($request->all())->validate();

        $post = $request->input('post');

        DB::table('posts')->insert([
            'post' => $post,
            'user_id' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('top');
    }


    public function deleteValidator(array $data)
    {
        return Validator::make(
            $data,
            [
                'id' => ['required', 'regex:/^[0-9]+$/']
            ],
            [
                'id.required' => 'メッセージの削除に失敗しました。もう一度やり直してください。',
                'id.regex' => 'メッセージの削除に失敗しました。もう一度やり直してください。',
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

    // public function delete(Request $request)
    // {
    //     $this->deleteValidator($request->all())->validate();

    //     $id = $request->input('id');

    //     // 投稿が存在し、現在のユーザーが所有者であることを確認
    //     $post = DB::table('posts')
    //         ->where('id', $id)
    //         ->where('user_id', Auth::id())
    //         ->first();

    //     if (!$post) {
    //         return redirect('/top')->with('error', '投稿が見つからないか、削除する権限がありません。');
    //     }

    //     // 確認メッセージをセッションに保存
    //     return redirect('/top')->with('confirmDelete', [
    //         'id' => $post->id,
    //         'message' => '本当にこの投稿を削除しますか？'
    //     ]);
    // }


    // public function destroyPost(Request $request)
    // {
    //     $this->deleteValidator($request->all())->validate();

    //     $id = $request->input('id');

    //     DB::table('posts')
    //         ->where('id', $id)
    //         ->where('user_id', Auth::id())
    //         ->delete();

    //     return redirect('/top')->with('success', '投稿が削除されました。');
    // }




    public function editValidator(array $data)
    {
        return Validator::make(
            $data,
            [
                'id' => ['required', 'regex:/^[0-9]+$/']
            ],
            [
                'id.required' => 'メッセージ情報を取得できませんでした。もう一度やり直してください。',
                'id.regex' => 'メッセージ情報を取得できませんでした。もう一度やり直してください。',
            ]
        );
    }
    public function editPost($id)
    {
        $this->editValidator(['id' => $id])->validate();


        $post = DB::table('posts')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->select('id', 'post', 'user_id')
            ->first();

        if (!$post) {
            return redirect()->route('top')->with('error', '投稿が見つかりませんでした。');
        }


        return view('top.update', ['post' => $post]);
    }

    public function updateValidator(array $data)
    {
        return Validator::make(
            $data,
            [
                'id' => ['required', 'regex:/^[0-9]+$/'],
                'post' => ['required', 'string', 'max:400']
            ],
            [
                'id.required' => 'メッセージの編集に失敗しました。もう一度やり直してください。',
                'id.regex' => 'メッセージの編集に失敗しました。もう一度やり直してください。',
                'post.required' => 'メッセージを入力してください。',
                'post.max' => 'メッセージは400文字以内で入力してください。',
            ]
        );
    }
    public function update(Request $request)
    {
        $this->updateValidator($request->all())->validate();

        $id = $request->input('id');
        $post = $request->input('post');
        DB::table('posts')
            ->where('id', $request->input('id'))
            ->update(['post' => $post]);

        return redirect('/top');
    }
}
