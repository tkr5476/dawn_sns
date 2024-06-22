@extends('layouts.app')


@section('content')
<h1>Follow List</h1>

<table>
    @foreach ($followUsers as $followUser)
    <tr>
        <td>
            <a href="/user/{{ $followUser->id }}/profile">
                <img src="{{asset('/images/' . $followUser->image)}}" alt="ユーザーアイコン">
            </a>
        </td>
        <td>{{ $followUser->name }}</td>
    <tr>
        @endforeach
</table>

<hr>

<table>
    @foreach ($followPosts as $followPost)
    <tr>
        <td>
            <a href="/user/{{ $followPost->id }}/profile">
                <img src="{{asset('/images/' . $followPost->image)}}" alt="ユーザーアイコン">
            </a>
        </td>
        <td>{{ $followPost->name }}</td>
        <td>{{ $followPost->post }}</td>
        <td>{{ $followPost->created_at }}</td>
    </tr>
    @endforeach
</table>

@endsection
