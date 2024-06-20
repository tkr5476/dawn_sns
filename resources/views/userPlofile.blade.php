@extends('layouts.app')

@section('content')
    <h1>ユーザープロフィール</h1>
    <img src="{{ $userProfile->image }}" alt="ユーザーアイコン">
    <h2>{{ $userProfile->name }}</h2>
@endsection
