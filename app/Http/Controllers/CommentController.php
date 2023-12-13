<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Notifications\CommentNotification;
use Illuminate\Support\Facades\Notification;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'comment_body' => 'required|max:255',
            'post_id' => 'required|exists:posts,id',
        ]);

        $comment = new Comment;
        $comment->comment_body = $request->input('comment_body');
        $comment->user_id = auth()->id();
        $comment->post_id = $request->input('post_id');
        $comment->save();

        $comment->load('user');

        Notification::route('mail', $comment->post->user->email)
            ->notify(new CommentNotification($comment));

        return response()->json([
            'success' => true,
            'comment' => $comment,
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if (auth()->id() === $comment->user_id) {
            $comment->deleteComment();

            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'You do not have permission to delete this comment.',
            ]);
        }
    }
}
