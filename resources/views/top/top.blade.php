@extends('layouts.login')



@section('content')
<form action="/post/create" method="post">
    @csrf
    <div class="form-group">
        <input type="text" name="post" class="form-control" placeholder="投稿内容">
    </div>
    <div class="pull-right submit-btn">
        <button type="submit" class="btn">
            <img src="{{asset('/images/post.png')}}" alt="追加ボタン">
        </button>
    </div>
    @error('post')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
</form>



<table class='table table-hover'>
    @foreach ($posts as $post)
    <tr>
        <td>
            <a href="/user/{{ $post->u_id }}/profile">
            <img src="{{asset('/images/' . $post->image)}}" alt="プロフィール画像">
            </a>
        </td>
        <td>{{ $post->name}}</td>
        <td>{{ $post->post }}</td>
        <td>{{ $post->created_at }}</td>
        <td>
            <a href="/post/{{ $post->p_id }}/update-form">
                <img src="{{asset('/images/edit.png')}}" alt="編集ボタン">
            </a>
        </td>
        <td>
        <form action="/post/delete" method="post">
            @method('DELETE')
            @csrf
            <input type="hidden" name="id" value="{{ $post->p_id }}">
            <button type="submit" class="delete-btn btn btn-danger">
                <img src="{{asset('/images/trash.png')}}" alt="削除ボタン">
            </button>
        </form>
        </td>
    </tr>
    @endforeach
</table>

@endsection
