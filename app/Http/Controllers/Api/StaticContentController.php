<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StaticContent;

class StaticContentController extends Controller
{
    /**
     * Display a listing of the static content.
     * @OA\Get(
     *     path="/api/static-content",
     *     summary="Get Static Content",
     *     tags={"Static Content"},
     *   security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",  
     *        @OA\JsonContent(
     *            @OA\Property(property="status", type="boolean", example=true),
     *         
     *        ),
     * )
     *  ),
     * 
     */
    public function index()
    {
        $staticContents = StaticContent::orderBy('id', 'desc')->get(['id', 'slug', 'title', 'content', 'image']);
        // Add full image URL
        $staticContents->transform(function ($item) {
            $item->image = $item->image ? asset($item->image) : null;
            return $item;
        });
        return response()->json([
            'status' => true,
            'data' => $staticContents
        ]);
    }

    /**
     * Display the specified static content.
     */
    public function show($id)
    {
        $static = StaticContent::find($id);
        if (!$static) {
            return response()->json(['status' => false, 'message' => 'Not found'], 404);
        }
        $static->image = $static->image ? asset($static->image) : null;
        return response()->json([
            'status' => true,
            'data' => $static
        ]);
    }
}
