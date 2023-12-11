@extends('layouts.app')

@section('content')
    <h2>User Details: {{ $user->name }}</h2>

    <h3>Posts</h3>
    <ul>
        @foreach ($posts as $post)
            <li>{{ $post->post_body }}</li>
        @endforeach
    </ul>

    <h3>Comments</h3>
    <ul>
        @foreach ($comments as $comment)
            <li>{{ $comment->comment_body }}</li>
        @endforeach
    </ul>

    <p><a href="{{ route('posts.index') }}">Back to Posts</a></p>
@endsection