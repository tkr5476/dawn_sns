@extends('layouts.app')


@section('content')


    <form action="/user/search/1" method="post">
        @csrf
        <input type="text" name="name">
        <button type="submit">検索</button>
    </form>

    @foreach($user as $user)
        <p>{{$user->name}}</p>
    @endforeach

@endsection

