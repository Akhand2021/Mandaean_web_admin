<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PushNotificationService;
use Illuminate\Support\Str;
use App\Models\User;

class PostController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/posts",
     *     tags={"Community"},
     *     summary="Get all posts",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of posts with user, likes, comments, shares."
     *     )
     * )
     */
    public function index()
    {
        $posts = Post::with(['user'])->withCount(['likes', 'comments', 'shares'])->latest()->paginate(10);
        return PostResource::collection($posts);
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{post}",
     *     tags={"Community"},
     *     summary="Get a single post with details",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Parameter(name="post", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Post details."
     *     )
     * )
     */
    public function show(Post $post)
    {
        $post->load(['user'])->loadCount(['likes', 'comments', 'shares']);
        return new PostResource($post);
    }

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     tags={"Community"},
     *     summary="Create a new post",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"content"},
     *                 @OA\Property(property="content", type="string", example="My story..."),
     *                 @OA\Property(property="image", type="file", description="Image file to upload")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post created."
     *     )
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $data['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName());
            $destination = public_path('uploads/community');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $image->move($destination, $imageName);
            $data['image_url'] = 'uploads/community/' . $imageName;
        }

        $post = Post::create($data);
        $post->load(['user'])->loadCount(['likes', 'comments', 'shares']);

        // ✅ Send Push Notification to all other users
        $tokens = User::where('id', '!=', Auth::id())
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->toArray();

        if (!empty($tokens)) {
            app(PushNotificationService::class)->sendNotification(
                $tokens,
                '📢 New Community Post!',
                $post->user->name . ' just posted: ' . Str::limit($post->content, 50),
                [
                    'post_id' => $post->id,
                    'user_id' => $post->user_id
                ]
            );
        }

        return (new PostResource($post))->response()->setStatusCode(201);
    }


    /**
     * @OA\Put(
     *     path="/api/posts/{post}",
     *     tags={"Community"},
     *     summary="Update a post",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Parameter(name="post", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"content"},
     *                 @OA\Property(property="content", type="string", example="Updated story..."),
     *                 @OA\Property(property="image", type="file", description="Image file to upload (optional)")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post updated."
     *     )
     * )
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);
        $data = $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName());
            $destination = public_path('uploads/community');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $image->move($destination, $imageName);
            $data['image_url'] = 'uploads/community/' . $imageName;
        }
        $post->update($data);
        $post->load(['user'])->loadCount(['likes', 'comments', 'shares']);
        return new PostResource($post);
    }

    /**
     * @OA\Delete(
     *     path="/api/posts/{post}",
     *     tags={"Community"},
     *     summary="Delete a post",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Parameter(name="post", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Post deleted."
     *     )
     * )
     */
    public function destroy(Post $post)
    {
        // $this->authorize('delete', $post);
        $post->delete();
        return response()->json(['message' => 'Deleted']);
    }

    /**
     * @OA\Get(
     *     path="/api/my-posts",
     *     tags={"Community"},
     *     summary="Get posts of the authenticated user",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of user's own posts."
     *     )
     * )
     */
    public function myPosts()
    {
        $posts = Post::with(['user'])->withCount(['likes', 'comments', 'shares'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        return PostResource::collection($posts);
    }
}
