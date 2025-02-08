<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponses;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;


class PostController extends Controller implements HasMiddleware

{
    public static function middleware(){
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PostResource::collection(Post::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $post = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'content' => ['required', 'string', 'min:10'],
            ]);
            $user = $request->user();
            $user->posts()->create($post);
            return ApiResponses::sendSuccessResponse("Post created", null, true, $post);
        } catch (\Exception $e) {
            return ApiResponses::sendErrorResponse("Something went wrong", $e);
        }
        }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $post = Post::findOrFail($id);
            return ApiResponses::sendSuccessResponse("Post found", null, true, new PostResource($post));
        } catch (\Exception $e) {
            return ApiResponses::sendErrorResponse("Something wne wrong", $e);
        }
    }
    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Post $post)
    {
    try {
        Gate::authorize('user-post-authorization', $post);

        $updated_post = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:10'],
        ]);
        $post->update($updated_post);
        return ApiResponses::sendSuccessResponse("Post updated", null, true, $post);
    } catch (\Exception $e) {
        return ApiResponses::sendErrorResponse("Something went wrong", $e);
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('user-post-authorization', $post);
        $post->delete();
        return [ "message" => "Post deleted" ];
    }

    public function addComment(Request $request)
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
}
