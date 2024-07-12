@extends('layouts.login')

@section('content')

@if($userProfile)

<table class="table">
    <tr>
        <th>
            <img src="{{ asset('storage/userIcon/'. $userProfile->image) }}" alt="ユーザーアイコン" class="img-thumbnail rounded-circle w-30 h-30 col-2 object-fit-cover">
        </th>
        <td>
            @if ($followings->contains('user_id',$userProfile->id))
            <form action="/user/follow/delete" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $userProfile->id }}">
                <button type="submit" class="btn btn-info">フォローをはずす</button>
            </form>
            @else
            <form action="/user/follow/add" method="post">
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
</table>

<table>
    @foreach ($userPosts as $userPost)

    <tr>
        <th>
            <p>{{ $userPost->post }}</p>
        </th>
        <td>
            <p>{{ $userPost->created_at }}</p>
        </td>
    </tr>
    @endforeach
</table>

@else
<p>ユーザーが見つかりません。</p>
@endif

@endsection
