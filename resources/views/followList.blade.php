@extends('layouts.app')


@section('content')
<h1>Follow List</h1>

<table>
    @foreach ($followIcons as $followIcon)
    <tr>
        <td class="btn btn-primary">
            <a href="/followList/{{ $followIcon->id }}/profile">
                <img src="{{ $followIcon->image }}" alt="ユーザーアイコン">
            </a>
        </td>
        <td>{{ $followIcon->name }}</td>
    <tr>
        @endforeach
</table>

<hr>

<table>
    @foreach ($followPosts as $followPost)
    <tr>
        <td class="btn btn-primary">
            <a href="/followList/{{ $followPost->id }}/profile">
                <img src="{{ $followPost->image }}" alt="ユーザーアイコン">
            </a>
        </td>
        <td>{{ $followPost->name }}</td>
        <td>{{ $followPost->post }}</td>
        <td>{{ $followPost->created_at }}</td>
    </tr>
    @endforeach
</table>

@endsection
