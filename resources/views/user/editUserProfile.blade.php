@extends('layouts.login')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<table class="table d-flex justify-content-center mt-5">
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <tr>
            <th>
                <label for="image" class="form-label">
                    <img src="{{ asset('storage/userIcon/' . $user->image) }}" alt="ログインユーザーアイコン" class="img-thumbnail rounded-circle w-30 h-30 col-2 object-fit-cover">
                </label>
            </th>
            <td>
                <input type="file" id="image" class="form-control" name="image">
            </td>
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
            <td colspan="2">
                <button type="submit" class="btn btn-info">更新</button>
            </td>
        </tr>
    </form>
</table>
@endsection
