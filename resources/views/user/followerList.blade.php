@extends('layouts.login')


@section('content')
<h1>Follower List</h1>

<table>
    @foreach ($followerUsers as $followerUser)
    <tr>
        <td>
            <a href="/user/{{ $followerUser->id }}/profile">
                <img src="{{asset('storage/userIcon/' . $followerUser->image)}}" alt="ユーザーアイコン" class="img-thumbnail rounded-circle w-30 h-30 col-2 object-fit-cover">
            </a>
        </td>
        <td>{{ $followerUser->name }}</td>
    <tr>
        @endforeach
</table>

<hr>

<table>
    @foreach ($followerPosts as $followerPost)
    <tr>
        <td>
            <a href="/user/{{ $followerPost->id }}/profile">
                <img src="{{asset('storage/userIcon/' . $followerPost->image)}}" alt="ユーザーアイコン" class="img-thumbnail rounded-circle w-30 h-30 col-2 object-fit-cover">
            </a>
        </td>
        <td>{{ $followerPost->name }}</td>
        <td>{{ $followerPost->post }}</td>
        <td>{{ $followerPost->created_at }}</td>
    </tr>
    @endforeach
</table>

@endsection
