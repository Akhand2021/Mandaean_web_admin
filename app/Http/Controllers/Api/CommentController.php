<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/posts/{post}/comment",
     *     tags={"Community"},
     *     summary="Add a comment to a post",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Parameter(name="post", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"content"},
     *                 @OA\Property(property="content", type="string", example="Nice post!")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment added.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="post_id", type="integer", example=1),
     *                 @OA\Property(property="content", type="string", example="Nice post!"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     )
     * )
     */
    public function comment(Request $request, Post $post)
    {
        $data = $request->validate(['content' => 'required|string']);
        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $data['content']
        ]);
        return response()->json($comment, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{post}/comments",
     *     tags={"Community"},
     *     summary="Get comments for a post",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Parameter(name="post", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="List of comments for a post."
     *     )
     * )
     */
    public function getComments(Post $post)
    {
        $comments = $post->comments()->with('user')->latest()->paginate(10);
        return CommentResource::collection($comments);
    }

    /**
     * @OA\Put(
     *     path="/api/comments/{comment}",
     *     tags={"Community"},
     *     summary="Update a comment",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Parameter(name="comment", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"content"},
     *                 @OA\Property(property="content", type="string", example="Nice post!")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment updated.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="post_id", type="integer", example=1),
     *                 @OA\Property(property="content", type="string", example="Nice post!"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);
        $data = $request->validate(['content' => 'required|string']);
        $comment->update($data);
        return new CommentResource($comment);
    }

    /**
     * @OA\Delete(
     *     path="/api/comments/{comment}",
     *     tags={"Community"},
     *     summary="Delete a comment",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Parameter(name="comment", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Comment deleted.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="message", type="string", example="Deleted")
     *             )
     *         )
     *     )
     * )
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
