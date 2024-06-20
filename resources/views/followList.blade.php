@extends('layouts.app')


@section('content')
<h1>Follow List</h1>

<table>
    @foreach ($followIcons as $followIcon)
    <tr>
        <td>
            <form action="/follow/profile" method="post">
                <input type="hidden" name="id" value="{{ $followIcon->id }}">
                <button type="submit" class="btn btn-primary">
                    <img src="{{ $followIcon->image }}" alt="ユーザーアイコン">
                </button>
            </form>
        </td>
        <td>{{ $followIcon->name }}</td>
    <tr>
    @endforeach
</table>

<hr>

<table>
    @foreach ($followPosts as $followPost)
    <tr>
        <td>
            <form action="/follow/profile" method="post">
                <input type="hidden" name="id" value="{{ $followPost->id }}">
                <button type="submit" class="btn btn-primary">
                    <img src="{{ $followPost->image }}" alt="ユーザーアイコン">
                </button>
            </form>
        </td>
        <td>{{ $followPost->name }}</td>
        <td>{{ $followPost->post }}</td>
        <td>{{ $followPost->created_at }}</td>
    </tr>
    @endforeach
</table>

@endsection
