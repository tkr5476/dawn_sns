@extends('layouts.login')



@section('content')
<form action="/post/create" method="post">
    @csrf
    <div class="form-group">
        <input type="text" name="post" class="form-control" placeholder="投稿内容">
    </div>
    <div class="pull-right submit-btn">
        <button type="submit" class="btn btn-success btn-sm shadow-sm">
            <img src="{{asset('/storage/images/post.png')}}" alt="追加ボタン">
        </button>
    </div>
    @error('post')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
</form>



<table class='table table-hover'>
    @foreach ($posts as $post)
    <tr>
        <th>
            <a href="/user/{{ $post->u_id }}/profile">
                <img src="{{asset('storage/userIcon/'. $post->image)}}" alt="デフォルトのプロフィール画像" class="img-thumbnail rounded-circle w-30 h-30 col-2 object-fit-cover">
            </a>
        </th>
        <td>{{ $post->name}}</td>
        <td>{{ $post->post }}</td>
        <td>{{ $post->created_at }}</td>
        @if ($post->u_id == Auth::id())
        <td>
            <a href="{{ route('post.edit', ['id' => $post->p_id]) }}" class="btn btn-primary">
                <img src="{{asset('storage/images/edit.png')}}" alt="編集ボタン">
            </a>
        </td>
        <td>
        <form action="/post/delete" method="post" onclick="return confirm('このつぶやきを削除します。よろしいでしょうか？')" >
            @method('DELETE')
            @csrf
            <input type="hidden" name="id" value="{{ $post->p_id }}">
            <button type="submit" class="delete-btn btn btn-danger">
                <img src="{{asset('/storage/images/trash.png')}}" alt="削除ボタン">
            </button>

            @error('id')
            <p class="text-danger">{{$message}}</p>
            @enderror
            </form>
        </td>
        @endif
    </tr>
    @endforeach
</table>

@endsection
