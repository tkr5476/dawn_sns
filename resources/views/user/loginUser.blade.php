@extends('layouts.login')

@section('content')

<table>
    <tr>
        <th><img src="{{ asset('/images/' . $loginUser->image) }}" alt="ログインユーザーアイコン"></th>
    </tr>

    <tr>
        <th>
            <h3>Name</h3>
        </th>
        <td>
            <h1>{{ $loginUser->name }}</h1>

        </td>
    </tr>

    <tr>
        <th>
            <p>Email</p>
        </th>
        <td>
            <p>{{ $loginUser->email }}</p>
        </td>
    </tr>
    <tr>
        <th>
            <p>Bio</p>
        </th>

        <td>
            <p>{{ $loginUser->bio }}</p>
        </td>
    </tr>
    <tr>
        <td>
            <a href="/editUserProfile" class="btn btn-info">変更画面へ</a>
        </td>
    </tr>
</table>


@foreach ($loginUserPosts as $loginUserPost)
<table>
    <tr>
        <th>
            <p>{{ $loginUserPost->post }}</p>
            {{--投稿の編集機能をここにもあるといいかも--}}
        </th>
        <td>
            <p>{{ $loginUserPost->created_at }}</p>
        </td>
    </tr>
</table>
@endforeach


@if ($loginUser == null)
<p>ユーザー情報の取得に失敗しました。もう一度やり直してください。</p>
@endif

@endsection
