@extends('layouts.app')

@section('content')
<h1>Follower List</h1>

<table>
    @foreach ($followerUsers as $followerUser)
    <tr>
        <td>
            <a href="/user/{{ $followerUser->id }}/profile">
                <img src="{{asset('/images/' . $followerUser->image)}}" alt="ユーザーアイコン">
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
                <img src="{{asset('/images/' . $followerPost->image)}}" alt="ユーザーアイコン">
            </a>
        </td>
        <td>{{ $followerPost->name }}</td>
        <td>{{ $followerPost->post }}</td>
        <td>{{ $followerPost->created_at }}</td>
    </tr>
    @endforeach
</table>

@endsection
