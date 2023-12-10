@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
    <form method="POST" action="{{ route('posts.store') }}">
        @csrf
        <p>Post: <input type="text" name="post_body"></p>
        <input type="submit" value="Submit">
        <a href=" {{ route('posts.index') }}">Cancel</a>
    </form>

@endsection