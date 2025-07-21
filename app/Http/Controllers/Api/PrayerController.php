<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prayer;
use App\Http\Resources\PrayerResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrayerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/prayers",
     *     tags={"Prayer"},
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     summary="Get all prayers grouped by prayer_time (morning, afternoon, evening)",
     *     description="Returns prayers grouped by prayer_time. filter by day",
     *     @OA\Parameter(
     *         name="day",
     *         in="query",
     *         required=false,
     *         description="Filter prayers by day (e.g., Barkha, Reshma, Monday, etc.)",
     *         @OA\Schema(type="string", enum={"Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"})
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

        // Build the where conditions properly
        if ($request->has('day')) {
            $query->where(function ($q) use ($request) {
                $q->where('prayer_type', $request->day)
                    ->orWhere('prayer_type', 'Barkha')
                    ->orWhere('prayer_type', 'Reshma');
            });
        } else {
            $query->whereIn('prayer_type', ['Barkha', 'Reshma']);
        }

        $prayers = $query->select('id', 'title', 'subtitle', 'description', 'prayer_time', 'prayer_type', 'prayer_date', 'other_info', 'ar_title', 'ar_subtitle', 'ar_description', 'pe_other_info')
            ->orderBy('prayer_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get(); // Execute the query here, not later

        $result = [];
        $prayerTypes = ['Barkha', 'Reshma', $request->day];
        $prayerTimes = ['morning', 'afternoon', 'evening'];

        // Group prayers by prayer_type first
        $groupedByType = $prayers->groupBy('prayer_type');

        foreach ($prayerTypes as $type) {
            $result[$type] = [];

            // Get prayers for this specific type
            $prayersForType = $groupedByType->get($type, collect());

            // Then group by prayer_time
            $groupedByTime = $prayersForType->groupBy('prayer_time');

            foreach ($prayerTimes as $prayer_time) {
                $result[$type][$prayer_time] = PrayerResource::collection(
                    $groupedByTime->get($prayer_time, collect())
                );
            }
        }


        return response()->json($result);
    }
}
