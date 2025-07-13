<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReligiousOccasion;
use Illuminate\Http\Resources\Json\JsonResource;

class ReligiousOccasionController extends Controller
{
    // List all religious occasions
    /**
     * @OA\Get(
     *     path="/api/religious-occasions",
     *     tags={"Religious Occasions"},
     *     summary="Get all religious occasions",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="year",
     *         in="query",
     *         required=false,
     *         description="Filter occasions by year (defaults to current year)",
     *         @OA\Schema(type="integer", example=2025)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of religious occasions.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *    @OA\Response( 
     *       response=404,  
     *      description="Not found",
     *     @OA\JsonContent(
     *        @OA\Property(property="success", type="boolean", example=false),
     *       @OA\Property(property="message", type="string", example="Not found")
     *      )
     *  )
     * )
     */
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $occasions = ReligiousOccasion::where('year', $year)->orderBy('year', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $occasions
        ]);
    }

    // Show a single religious occasion
    /**
     * @OA\Get(
     *     path="/api/religious-occasions/{id}",
     *    tags={"Religious Occasions"},
     *    summary="Get a single religious occasion by ID",
     *    security={{"apiKey":{}},{"bearerAuth": {}}},
     * *    @OA\Parameter(
     *        name="id",    
     *        in="path",
     *       required=true,
     *       description="ID of the religious occasion",
     *      @OA\Schema(type="integer")
     * *    ),
     *   @OA\Response(
     *      response=200,
     *     description="Religious occasion details.",
     *   @OA\JsonContent(
     *      @OA\Property(property="success", type="boolean", example=true),
     *     @OA\Property(property="data", type="object")
     *   )
     * *   ),
     *   @OA\Response(
     *      response=404,
     *     description="Not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example=false),
     *      @OA\Property(property="message", type="string", example="Not found")
     *    )
     *  )
     * )
     */
    public function show($id)
    {
        $occasion = ReligiousOccasion::find($id);
        if (!$occasion) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $occasion
        ]);
    }
}
