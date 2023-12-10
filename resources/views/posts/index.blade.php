@extends('layouts.app')

@section('content')
    <p>Index of your hopelessness</p>
    <ul>
        @foreach ($posts as $post)
            <li><a href="/posts/{{ $post->id }}">{{ $post->post_body }}</a></li>
        @endforeach
    </ul>
    {{ $posts->links() }}
    <a href=" {{ route('posts.create') }}">Create Post</a>
@endsection