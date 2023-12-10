@extends('layouts.app')

@section('title', 'Post body')

@section('content')
    <ul>
        <li>{{ $post->post_body }}</li>
        <li>Posted by: {{ $post->user->name }}</li>
    </ul>
    
    @if(auth()->id() === $post->user_id)
        <form method="POST" action="{{ route('posts.destroy', $post->id) }}">
            @csrf
            @method('DELETE')
            <button type="submit">Delete Post</button>
        </form>
    @else
        <p>You do not have permission to delete this post.</p>
    @endif
@endsection
