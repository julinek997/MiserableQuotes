@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
    <h1>Edit Post</h1>

    <form method="POST" action="{{ route('posts.update', $post->id) }}">
        @csrf
        @method('PATCH')

        <label for="post_body">Post Body:</label>
        <textarea name="post_body" required>{{ $post->post_body }}</textarea>

        <button type="submit">Update</button>
    </form>
@endsection
