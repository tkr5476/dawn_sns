@extends('layouts.login')

@section('content')

<table>
    <tr>
        <th><img src="{{ asset('/images/' . $userProfile->image) }}" alt="ユーザーアイコン"></th>
        <td>
            @if ($followings->contains('user_id',$userProfile->id))
            <form action="/profile/follow/delete" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $userProfile->id }}">
                <button type="submit" class="btn btn-info">フォローをはずす</button>
            </form>
            @else
            <form action="/profile/follow/add" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $userProfile->id }}">
                <button type="submit" class="btn btn-outline-info text-dark">フォローする</button>
            </form>

            @endif
        </td>
    </tr>

    <tr>
        <th>
            <h3>Name</h3>
        </th>
        <td>
            <h1>{{ $userProfile->name }}</h1>

        </td>
    </tr>

    <tr>
        <th>
            <p>Bio</p>
        </th>

        <td>
            <p>{{ $userProfile->bio }}</p>
        </td>
    </tr>
    {{--
    <tr>
        <td>
            @if ($userProfile->contains('user_id',$userProfile->id))
            <form action="/user/follow/delete" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $userProfile->id }}">
    <button type="submit" class="btn btn-info">フォローをはずす</button>
    </form>
    @else
    <form action="/user/follow/add" method="post">
        @csrf
        <input type="hidden" name="id" value="{{ $user->id }}">
        <button type="submit" class="btn btn-outline-info text-dark">フォローする</button>
    </form>
    @endif
    </td>--}}
</table>

@foreach ($userPosts as $userPost)
<table>
    <tr>
        <th>
            <p>{{ $userPost->post }}</p>
        </th>
        <td>
            <p>{{ $userPost->created_at }}</p>
        </td>
    </tr>
</table>
@endforeach
@endsection
