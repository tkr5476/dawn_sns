@extends('layouts.login')

@section('content')


<form action="/user/search/again" method="post">
    @csrf
    <input type="text" name="name">
    <button type="submit">検索</button>
    @isset($keyword)
    <p>検索ワード：{{$keyword}}</p>
    @endisset


</form>

@foreach($users as $user)
<table>
    <tr>
        <td>
            <a href="/user/{{ $user->id }}/profile">
                <img src="{{asset('/images/' . $user->image)}}" alt="ユーザーアイコン">
            </a>
        </td>
        <td>{{$user->name}}</td>
        <td>
            @if ($followings->contains('user_id',$user->id))
            <form action="/user/follow/delete" method="post">
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
</table>
@endforeach

@endsection
