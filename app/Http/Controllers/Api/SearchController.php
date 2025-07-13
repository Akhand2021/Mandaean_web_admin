<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Mandanism;
use App\Models\RecentSearch;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/search",
     *     tags={"Search"},
     *     summary="Search Products and Mandanism Categories",
     *     description="Search for products and mandanism categories based on the search term.",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=true,
     *         description="Search term for products and mandanism categories.",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Search results fetched successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data fetched."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="recent_search", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="No search found or validation error.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No search found."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function Search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'required'
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response([
                'status' => false,
                'message' => $error,
                'data' => []
            ], 422);
        }

        $id = Auth::id();
        $searchArray = [];
        $search = $request->search;
        $product = Product::where('status', 'active')
            ->where('name', 'LIKE', '%' . $search . '%')
            ->get();

        foreach ($product as $key => $value) {
            $array['id'] = $value->id;
            $array['name'] = $value->name;
            $array['section'] = 'product';
            array_push($searchArray, $array);
        }

        $mandanism = Mandanism::where('title', 'LIKE', '%' . $search . '%')
            // ->orWhere('group', 'LIKE', '%'.$search.'%')
            // ->orWhere('description', 'LIKE', '%'.$search.'%')
            ->get();

        foreach ($mandanism as $key1 => $value1) {
            $array['id'] = $value1->id;
            $array['name'] = $value1->title;
            $array['section'] = 'mandanism category';
            array_push($searchArray, $array);
        }

        $recent = RecentSearch::select('search_text')->where(['user_id' => $id])->groupBy('search_text')->take(5)->get();

        RecentSearch::create([
            'user_id' => $id,
            'search_text' => $search,
        ]);

        if (count($searchArray) > 0) {
            return response([
                'status' => true,
                'message' => 'Data fetched.',
                'data' => $searchArray,
                'recent_search' => $recent
            ], 201);
        } else {
            return response([
                'status' => false,
                'message' => 'No search found.',
                'data' => [],
                'recent_search' => $recent
            ], 422);
        }
    }
}
