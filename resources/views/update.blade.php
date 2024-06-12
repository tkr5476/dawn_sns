@extends('layouts.app')


    @section('content')
    <form action="/post/update" method="post">
        @method('PUT')
        @csrf
            <div class="form-group">
                <input type="hidden" name="id" value="{{$post->id}}">
                <input type="text" name="post" value="{{$post->post}}" class="form-control">
            </div>
            <div class="pull-right submit-btn">
                <button type="submit" class="btn btn-primary">更新</button>
            </div>
            </form>

    @endsection

    