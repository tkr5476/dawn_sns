@extends('layouts.login')

@section('content')

@if ($loginUser)
<table>
    <tr>
        <th><img src="{{ asset('storage/userIcon/' . $loginUser->image) }}" alt="ログインユーザーアイコン" class="img-thumbnail rounded-circle w-30 h-30 col-2 object-fit-cover"></th>
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
        </th>
        <td>
            <p>{{ $loginUserPost->created_at }}</p>
        </td>
    </tr>
</table>
@endforeach

@else
<p>ユーザー情報の取得に失敗しました。もう一度やり直してください。</p>
@endif

@endsection
