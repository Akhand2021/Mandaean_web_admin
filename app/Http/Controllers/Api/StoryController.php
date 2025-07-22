<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Models\StoryView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Http\Resources\StoryCollectionResource;
use App\Http\Resources\StoryResource;

class StoryController extends Controller
{
    // Create a new story
    /**
     * @OA\Post(
     *     path="/api/stories",
     *     tags={"Stories"},
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     summary="Create a new story",
     *     description="Upload a new story (image or video).",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="media", type="string", format="binary",
     *                     description="Image or video file to upload. Max size 10MB."),
     *             )
     *         )
     *     ),
     *     @OA\Response(    
     *        response=201,
     *       description="Story created successfully.",
     * *       @OA\JsonContent(
     *           @OA\Property(property="message", type="string", example="Story created successfully"),
     *           @OA\Property(property="story", type="object",
     *               @OA\Property(property="id", type="integer"),
     *               @OA\Property(property="media_url", type="string", example="https://example.com/storage/stories/story1.jpg"),
     *               @OA\Property(property="media_type", type="string", example="image"),
     *               @OA\Property(property="expires_at", type="string", format="date-time", example="2025-07-23T12:00:00Z"),
     *           )
     *       )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The media field is required."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="media", type="array", @OA\Items(type="string", example="The media field is required."))
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {

        // Check if file was uploaded successfully
        if (!$request->hasFile('media')) {
            return response()->json(['error' => 'No media file uploaded'], 400);
        }

        $file = $request->file('media');

        // Check for upload errors
        if (!$file->isValid()) {
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => 'File too large (exceeds upload_max_filesize)',
                UPLOAD_ERR_FORM_SIZE => 'File too large (exceeds MAX_FILE_SIZE)',
                UPLOAD_ERR_PARTIAL => 'File only partially uploaded',
                UPLOAD_ERR_NO_FILE => 'No file uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
            ];

            $errorCode = $file->getError();
            $errorMessage = $errorMessages[$errorCode] ?? 'Unknown upload error';

            return response()->json(['error' => $errorMessage], 400);
        }

        // Validate file
        $request->validate([
            'media' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:20480', // 20MB
        ]);

        try {
            $path = $file->store('stories', 'public');
            $mediaType = str_starts_with($file->getMimeType(), 'image') ? 'image' : 'video';

            $story = auth()->user()->stories()->create([
                'media_url'  => Storage::url($path),
                'media_type' => $mediaType,
                'expires_at' => Carbon::now()->addDay(),
            ]);

            return response()->json([
                'message' => 'Story created successfully',
                'story' => $story
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to upload file: ' . $e->getMessage()], 500);
        }
    }


    // Get all active stories (no following filter)
    /**
     * @OA\Get(
     *     path="/api/stories",
     *     tags={"Stories"},
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     summary="Get all active stories",
     *     description="Returns all active stories from users.",
     *     @OA\Response(
     *         response=200,
     *         description="List of active stories.",
     *     )
     * )
     */
    public function index()
    {
        $stories = Story::notExpired()
            ->with(['user', 'views'])
            ->latest()
            ->get()
            ->groupBy('user_id');

        $formattedStories = $stories->map(function ($userStories) {
            return new StoryCollectionResource($userStories);
        });

        return response()->json([
            'success' => true,
            'data' => $formattedStories->values(),
            'total_users' => $formattedStories->count()
        ]);
    }

    // View a specific story (marks as viewed)
    /**
     * @OA\Get(
     *     path="/api/stories/{story}",
     *     tags={"Stories"},
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     summary="View a specific story",
     *     description="Returns the details of a specific story and marks it as viewed.",
     *     @OA\Parameter(name="story", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Story details.",
     *     )
     * )
     */


    public function show($story)
    {
        $story = Story::find($story);
        if (empty($story)) {
            return response()->json(['message' => 'Story not found'], 404);
        }
        // Check if story is expired
        if ($story->expires_at < now()) {
            return response()->json([
                'success' => false,
                'message' => 'Story has expired'
            ], 404);
        }

        // Mark as viewed if not already viewed
        StoryView::firstOrCreate([
            'story_id' => $story->id,
            'user_id' => auth()->id(),
        ], [
            'viewed_at' => now()
        ]);

        // Load story with user and views
        $story->load(['user', 'views']);

        return response()->json([
            'success' => true,
            'data' => new StoryResource($story),
            'message' => 'Story retrieved successfully'
        ]);
    }


    // Get my stories only
    /**
     * @OA\Get(
     *     path="/api/my-stories",
     *     tags={"Stories"},
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     summary="Get my stories",
     *     description="Returns all active stories created by the authenticated user.",
     *     @OA\Response(
     *         response=200,
     *         description="List of my active stories.",
     *     )
     * )
     */

    public function myStories()
    {
        $stories = Story::where('user_id', auth()->id())
            ->notExpired()
            ->with(['views', 'user']) // Add user relationship for resource
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => StoryResource::collection($stories),
            'total' => $stories->count(),
            'message' => 'My stories retrieved successfully'
        ]);
    }


    // Delete a story
    /**
     * @OA\Delete(
     *     path="/api/stories/{story}",
     *     tags={"Stories"},
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     summary="Delete a story",
     *     description="Deletes a specific story if the user is the owner.",
     *     @OA\Parameter(name="story", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Story deleted successfully."
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized to delete this story."
     *     )
     * )
     */
    public function destroy(Story $story)
    {
        // Check if user owns the story
        if ($story->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Delete the media file from storage
        $mediaPath = str_replace('/storage/', '', parse_url($story->media_url, PHP_URL_PATH));
        Storage::disk('public')->delete($mediaPath);

        // Delete the story
        $story->delete();

        return response()->json(['message' => 'Story deleted successfully']);
    }

    // Get viewers for a specific story (for story owner)
    /**
     * @OA\Get(
     *     path="/api/stories/{story}/viewers",
     *     tags={"Stories"},
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     summary="Get viewers of a story",
     *     description="Returns the list of users who viewed a specific story.",
     *     @OA\Parameter(name="story", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="List of viewers for the story.",
     *     )
     * )
     */
    public function viewers(Story $story)
    {
        // Check if user owns the story
        if ($story->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $viewers = $story->views()
            ->with('user:id,name')
            ->orderBy('viewed_at', 'desc')
            ->get()
            ->map(function ($view) {
                return [
                    'user' => $view->user,
                    'viewed_at' => $view->viewed_at
                ];
            });

        return response()->json($viewers);
    }
}
