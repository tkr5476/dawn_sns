@extends('layouts.login')

@section('content')

<table>
    <tr>
        <th><img src="{{ asset('/images/' . $userProfile->image) }}" alt="ユーザーアイコン"></th>
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
