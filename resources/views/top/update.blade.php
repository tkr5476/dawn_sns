@extends('layouts.login')


    @section('content')
    <form action="/post/update" method="post">
        @method('PUT')
        @csrf
            <div class="form-group">
                <input type="hidden" name="id" value="{{$post->id}}">
                <input type="text" name="post" value="{{$post->post}}" class="form-control">
            </div>
            <div class="pull-right submit-btn">
                <button type="submit" class="btn btn-success btn-sm">
                    <img src="{{asset('storage/images/edit.png')}}" alt="更新ボタン">
                </button>
            </div>
            @error('post')
                <p class="text-danger">{{$message}}</p>
            @enderror
            </form>

    @endsection

