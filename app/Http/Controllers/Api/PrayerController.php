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
     *     summary="Get all prayers grouped by prayer_time (morning, afternoon, evening)",
     *     description="Returns prayers grouped by prayer_time. Optionally filter by date (YYYY-MM-DD).",
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         required=false,
     *         description="Filter prayers by date (YYYY-MM-DD)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of prayers grouped by prayer_time.",
     *         @OA\JsonContent(
     *             type="object")
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Prayer::query();
        if ($request->has('date')) {
            $query->whereDate('prayer_date', $request->date);
        }
        $prayers = $query->get()->groupBy('prayer_type');
        $result = [];
        $prayerTypes = ['Barkha', 'Reshma', 'Monday'];
        $prayerTimes = ['morning', 'afternoon', 'evening'];
        foreach ($prayerTypes as $type) {
            $result[$type] = [];
            $grouped = ($prayers->get($type, collect()))->groupBy('prayer_time');
            foreach ($prayerTimes as $prayer_time) {
                $result[$type][$prayer_time] = PrayerResource::collection($grouped->get($prayer_time, collect()));
            }
        }
        return response()->json($result);
    }
}
