@extends('layouts.login')

@section('content')
@error('id')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

<form action="/user/search/again" method="post">
    @csrf
    <input type="text" name="name">
    <button type="submit">検索</button>
    @isset($keyword)
    <p>検索ワード：{{$keyword}}</p>
    @endisset
</form>

<table class="table">
    @foreach($users as $user)
    <tr>
        <td>
            <a href="/user/{{ $user->id }}/profile">
                <img src="{{asset('storage/userIcon/' . $user->image)}}" alt="ユーザーアイコン"
                class="img-thumbnail rounded-circle col-2">
            </a>
        </td>
        <td class="col-4">{{$user->name}}</td>
        <td>
            @if ($followings->contains('user_id',$user->id))
            <form action="/user/follow/delete" method="post">
                @method('POST')
                @csrf
                <input type="hidden" name="id" value="{{ $user->id }}">
                <button type="submit" class="btn btn-info">フォローをはずす</button>
            </form>
            @else
            <form action="/user/follow/add" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $user->id }}">
                <button type="submit" class="btn btn-outline-info text-dark">フォローする</button>
            </form>

            @endif
        </td>
    </tr>
    @endforeach
</table>

@endsection
