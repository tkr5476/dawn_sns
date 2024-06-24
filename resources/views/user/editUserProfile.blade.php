@extends('layouts.login')

@section('content')

<table class="table ">
    <form action="/userProfile/update" method="post" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <tr>
            <th><img src="{{ asset('/images/' . $user->image) }}" alt="ログインユーザーアイコン"></th>
        </tr>

        <tr>
            <th>
                <label for="name" class="form-label">Name</label>
            </th>
            <td>
                <input type="text" id="name" class="form-control" name="name" value="{{ $user->name }}">
            </td>
        </tr>

        <tr>
            <th>
                <label for="email" class="form-label">Email</label>
            </th>
            <td>
                <input type="email" id="email" class="form-control" name="email" value="{{ $user->email }}">
            </td>
        </tr>

        <tr>
            <th>
                <label for="password" class="form-label">Password</label>
            </th>
            <td>
                <input type="password" id="password" class="form-control" name="password" placeholder="新しいパスワードを入力">
            </td>
        </tr>

        <tr>
            <th>
                <label for="password_confirmation" class="form-label">Password Confirm</label>
            </th>
            <td>
                <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="パスワードを再入力">
            </td>
        </tr>

        <tr>
            <th>
                <label for="bio" class="form-label">Bio</label>
            </th>
            <td>
                <textarea id="bio" class="form-control" name="bio" rows="3">{{ $user->bio }}</textarea>
            </td>
        </tr>

        <tr>
            <th>
                <label for="image" class="form-label">Icon Image</label>
            </th>
            <td>
                <input type="file" id="image" class="form-control" name="image">
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <button type="submit" class="btn btn-info">更新</button>
            </td>
        </tr>
    </form>
</table>

@endsection
