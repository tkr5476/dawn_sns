@extends('layouts.app')

@section('content')
    <h1>{{ $userProfile->name }}</h1>
    <p>{{ $userProfile->bio }}</p>
    <img src="{{ $userProfile->image }}" alt="ユーザーアイコン">

    @foreach ($userPosts as $userPost)
        <p>{{ $userPost->post }}</p>
        <p>{{ $userPost->created_at }}</p>
    @endforeach
@endsection

