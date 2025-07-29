<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\LikeResource;

class LikeController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/posts/{post}/like",
     *     tags={"Community"},
     *     summary="Like or unlike a post",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Parameter(name="post", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Like toggled."
     *     )
     * )
     */
    public function like(Post $post)
    {
        $user = Auth::user();
        $like = $post->likes()->where('user_id', $user->id)->first();
        if ($like) {
            $like->delete();
            return response()->json(['liked' => false]);
        } else {
            $post->likes()->create(['user_id' => $user->id]);
            return response()->json(['liked' => true]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{post}/likes",
     *     tags={"Community"},
     *     summary="Get likes for a post",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Parameter(name="post", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="List of likes for a post."
     *     )
     * )
     */
    public function getLikes(Post $post)
    {
        $likes = $post->likes()->with('user')->latest()->paginate(10);
        return LikeResource::collection($likes);
    }
}
