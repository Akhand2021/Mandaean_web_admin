<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Share;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $posts = Post::with(['user', 'likes', 'comments', 'shares'])->latest()->paginate(10);
        return response()->json($posts);
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
        $post->load(['user', 'likes', 'comments.user', 'shares']);
        return response()->json($post);
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
        return response()->json($post, 201);
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
        return response()->json($post);
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
        $this->authorize('delete', $post);
        $post->delete();
        return response()->json(['message' => 'Deleted']);
    }

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
     * @OA\Post(
     *     path="/api/posts/{post}/comment",
     *     tags={"Community"},
     *     summary="Add a comment to a post",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Parameter(name="post", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"content"},
     *             @OA\Property(property="content", type="string", example="Nice post!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment added."
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
        $posts = Post::with(['user', 'likes', 'comments', 'shares'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        return response()->json($posts);
    }
}
