@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
    <form method="POST" action="{{ route('posts.store') }}">
        @csrf
        <p>Post: <input type="text" name="post_body"></p>
        <p>Tags: 
            <select name="tags[]" multiple>
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->tag_body }}</option>
                @endforeach
            </select>
        </p>
        <p>New Tags (comma-separated): <input type="text" name="new_tags"></p>
        <input type="submit" value="Submit">
        <a href="{{ route('posts.index') }}">Cancel</a>
    </form>
@endsection
