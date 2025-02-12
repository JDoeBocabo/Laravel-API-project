<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponses;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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
     * Store a newly created resource in storage.
     */
    public function add_comment(Request $request, Post $post)
    {
        try {
            $comment = $request->validate([
                'comment' => ['required', 'string', 'min:5'],
                'postId' => ['required', 'integer'],
            ]);

            $user = $request->user();
            $post = Post::findOrFail($comment['postId']);

            $post->comments()->create([
                'comment' => $comment['comment'],
                'user_id' => $user->id
            ]);

            return ApiResponses::sendSuccessResponse("Comment added", null, true, $comment);
        } catch (ModelNotFoundException $e) {
            return ApiResponses::sendErrorResponse("Post not found", $e);
        } catch (\Exception $e) {
            return ApiResponses::sendErrorResponse("Something went wrong", $e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
