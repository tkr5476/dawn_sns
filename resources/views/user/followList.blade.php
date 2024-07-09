@extends('layouts.login')


@section('content')
<h1>Follow List</h1>

<table class="table">
    @foreach ($followUsers as $followUser)
    <tr>
        <td>
            <a href="/user/{{ $followUser->id }}/profile">
                <img src="{{asset('storage/userIcon/' . $followUser->image)}}" alt="ユーザーアイコン" class="img-thumbnail rounded-circle w-30 h-30 col-2 object-fit-cover">
            </a>
        </td>
        <td class="col-4">{{ $followUser->name }}</td>
    </tr>
    @endforeach
</table>

<hr>

<table class="table">
    @foreach ($followPosts as $followPost)
    <tr>
        <td>
            <a href="/user/{{ $followPost->id }}/profile">
                <img src="{{asset('storage/userIcon/' . $followPost->image)}}" alt="ユーザーアイコン" class="img-thumbnail rounded-circle w-30 h-30 col-2 object-fit-cover">
            </a>
        </td>
        <td>{{ $followPost->name }}</td>
        <td>{{ $followPost->post }}</td>
        <td>{{ $followPost->created_at }}</td>
    </tr>
    @endforeach
</table>

@endsection
