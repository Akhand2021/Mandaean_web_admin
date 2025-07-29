<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ShareResource;

class ShareController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/posts/{post}/share",
     *     tags={"Community"},
     *     summary="Share a post",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Parameter(name="post", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=201,
     *         description="Post shared."
     *     )
     * )
     */
    public function share(Post $post)
    {
        if ($post->shares()->where('user_id', Auth::id())->exists()) {
            return response()->json(['message' => 'Already shared'], 409);
        }
        $share = $post->shares()->create(['user_id' => Auth::id()]);
        return response()->json($share, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{post}/shares",
     *     tags={"Community"},
     *     summary="Get shares for a post",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Parameter(name="post", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="List of shares for a post."
     *     )
     * )
     */
    public function getShares(Post $post)
    {
        $shares = $post->shares()->with('user')->latest()->paginate(10);
        return ShareResource::collection($shares);
    }
}
