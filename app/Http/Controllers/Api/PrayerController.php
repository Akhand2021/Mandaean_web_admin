<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prayer;
use App\Http\Resources\PrayerResource;
use Illuminate\Http\Request;

class PrayerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/prayers",
     *     tags={"Prayer"},
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     summary="Get all prayers grouped by type (morning, afternoon, evening)",
     *     @OA\Response(
     *         response=200,
     *         description="List of prayers grouped by type."
     *     )
     * )
     */
    public function index(Request $request)
    {
        $prayers = Prayer::all()->groupBy('type');
        $result = [];
        foreach (['morning', 'afternoon', 'evening'] as $type) {
            $result[$type] = PrayerResource::collection($prayers->get($type, collect()));
        }
        return response()->json($result);
    }
}
