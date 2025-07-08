<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AudioResource;
use App\Models\Audio;
use Illuminate\Http\Request;

class AudioController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/audios",
     *     tags={"Audio"},
     *     summary="Get all audio files",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of audio files."
     *     )
     * )
     */
    public function index(Request $request)
    {
        $audios = Audio::with(['user', 'post'])->latest()->get();
        return AudioResource::collection($audios);
    }
}
