<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mandanism;
use App\Models\LatestNews;
use App\Models\HolyBook;
use App\Models\Bookmark;
use App\Models\Ritual;
use App\Models\Prayer;
use App\Models\Program;
use App\Http\Resources\MandanismResource;
use App\Http\Resources\MandanismDetailResource;
use App\Http\Resources\NewsResource;
use App\Http\Resources\NewsDetailResource;
use App\Http\Resources\RitualResource;
use App\Http\Resources\RitualDetailResource;
use App\Http\Resources\PrayerResource;
use App\Http\Resources\PrayerDetailResource;
use App\Http\Resources\HolyBookResource;
use App\Http\Resources\ProgramResource;
use App\Http\Resources\ProgramDetailResource;
use Auth;
use Validator;

class CategoryController extends Controller
{
    /**
     * @api {get} /mandanism-list Mandanism List
     * @OA\Get(
     *     path="/api/mandanism-list",
     *     tags={"Mandanism"},
     *     summary="Get Mandanism List",
     *     operationId="mandanismList",
     *     security={{"apiKey":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Mandanism List retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Mandanism List."),
     *         )
     *     ),
     * )
     */
    public function MandanismList(Request $request)
    {
        $data = Mandanism::where(['category' => 'our_history', 'status' => 'active'])->get();

        return response([
            'status' => true,
            'message' => 'Mandanism List.',
            'data' => MandanismResource::collection($data)
        ], 201);
    }
    /**
     * @OA\GET(
     *     path="/api/mandanism-detail/{id}",
     *     tags={"Mandanism"},
     *     summary="Get Mandanism Detail",
     *     operationId="mandanismDetail",
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the Mandanism item"
     *    ),
     *    @OA\Response(
     *        response=200,
     *       description="Mandanism Detail retrieved successfully",
     *       @OA\JsonContent(
     *           @OA\Property(property="status", type="boolean", example=true),
     *          @OA\Property(property="message", type="string", example="Mandanism Detail."),
     *    
     *       )
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="Mandanism item not found",
     *        @OA\JsonContent(
     *            @OA\Property(property="status", type="boolean", example=false),
     *            @OA\Property(property="message", type="string", example="Mandanism not found.")
     *        )
     *    )
     * )
     * @api {get} /mandanism-detail Mandanism Detail
     * @apiGroup Mandanism
     * @apiParam {Number} id Mandanism ID
     * @apiSuccess {Boolean} status Status of the request
     * @apiSuccess {String} message Message indicating success
     * @apiSuccess {Object} data Mandanism detail data
     * @apiSuccessExample {json} Success Response:
     */
    public function MandanismDetail($id)
    {
        $data = Mandanism::find($id);

        return response([
            'status' => true,
            'message' => 'Mandanism Detail.',
            'data' => new MandanismDetailResource($data)
        ], 201);
    }

    public function LatestNewsList(Request $request)
    {
        $country = $request->country;
        $data = LatestNews::where('status', 'active');
        if ($country) {
            $data = $data->where('country', $country);
        }
        $data = $data->get();

        return response([
            'status' => true,
            'message' => 'Latest News List.',
            'data' => NewsResource::collection($data)
        ], 201);
    }

    public function LatestNewsDetail($id)
    {
        $data = LatestNews::find($id);

        return response([
            'status' => true,
            'message' => 'Latest News Detail.',
            'data' => new NewsDetailResource($data)
        ], 201);
    }
    /**
     * @OA\GET(
     *     path="/api/holy-book-list",
     *     tags={"Holy Book"},
     *     summary="Get Holy Book List",
     *     operationId="holyBookList",
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string", enum={"holy", "author"}),
     *         description="Type of Holy Book (holy or author)"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Holy Book List retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Holy Book List."),
     *        )
     *     ),
     * )
     */
    public function HolyBookList(Request $request)
    {
        $id = Auth::id();
        $type = $request->type;
        if ($type == 'holy') {
            $data = HolyBook::where('type', 'holy')->where('status', 'active')->get();
        } else {
            $data = HolyBook::where('type', 'author')->where('status', 'active')->get();
        }
        return response([
            'status' => true,
            'message' => 'Holy Book List.',
            'data' => HolyBookResource::collection($data)
        ], 201);
    }

    public function Bookmark(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required',
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

        $data = Bookmark::where(['user_id' => $id, 'book_id' => $request->book_id])->first();
        if ($data) {
            $bookmark = ($data->bookmark == 'yes') ? 'no' : 'yes';
            Bookmark::where(['user_id' => $id, 'book_id' => $request->book_id])->update(['bookmark' => $bookmark]);

            return response([
                'status' => true,
                'message' => 'Bookmark saved.',
                'data' => []
            ], 201);
        } else {
            Bookmark::create([
                'user_id' => $id,
                'book_id' => $request->book_id,
                'bookmark' => 'yes'
            ]);

            return response([
                'status' => true,
                'message' => 'Bookmark saved.',
                'data' => []
            ], 201);
        }
    }

    public function RitualsList(Request $request)
    {
        $data = Ritual::where('status', 'active')->get();

        return response([
            'status' => true,
            'message' => 'Rituals List.',
            'data' => RitualResource::collection($data)
        ], 201);
    }

    public function RitualsDetail($id)
    {
        $data = Ritual::find($id);
        return response([
            'status' => true,
            'message' => 'Rituals Detail.',
            'data' => new RitualDetailResource($data)
        ], 201);
    }

    public function PrayerList(Request $request)
    {
        $data = Prayer::where('status', 'active')->get();

        return response([
            'status' => true,
            'message' => 'Prayer List.',
            'data' => PrayerResource::collection($data)
        ], 201);
    }

    public function PrayerDetail($id)
    {
        $data = Prayer::find($id);
        return response([
            'status' => true,
            'message' => 'Prayer Detail.',
            'data' => new PrayerDetailResource($data)
        ], 201);
    }

    public function ProgramList(Request $request)
    {
        $data = Program::where('status', 'active')->get();

        return response([
            'status' => true,
            'message' => 'Program List.',
            'data' => ProgramResource::collection($data)
        ], 201);
    }

    public function ProgramDetail($id)
    {
        $data = Program::find($id);

        return response([
            'status' => true,
            'message' => 'Program Detail.',
            'data' => new ProgramDetailResource($data)
        ], 201);
    }

    public function OurHistory(Request $request)
    {
        $data = Mandanism::where(['category' => 'our_history', 'status' => 'active'])->get();

        return response([
            'status' => true,
            'message' => 'Our History List.',
            'data' => MandanismResource::collection($data)
        ], 201);
    }

    public function OurHistoryDetail($id)
    {
        $data = Mandanism::where(['category' => 'our_history', 'id' => $id])->first();

        return response([
            'status' => true,
            'message' => 'Our History Detail.',
            'data' => new MandanismDetailResource($data)
        ], 201);
    }

    public function OurCulture(Request $request)
    {
        $data = Mandanism::where(['category' => 'our_culture', 'status' => 'active'])->get();

        return response([
            'status' => true,
            'message' => 'Our Culture List.',
            'data' => MandanismResource::collection($data)
        ], 201);
    }

    public function OurCultureDetail($id)
    {
        $data = Mandanism::where(['category' => 'our_culture', 'id' => $id])->first();

        return response([
            'status' => true,
            'message' => 'Our Culture Detail.',
            'data' => new MandanismDetailResource($data)
        ], 201);
    }

    public function OnlineArticles(Request $request)
    {
        $data = Mandanism::where(['category' => 'online_articles', 'status' => 'active'])->get();

        return response([
            'status' => true,
            'message' => 'Our Culture List.',
            'data' => MandanismResource::collection($data)
        ], 201);
    }

    public function OnlineArticleDetail($id)
    {
        $data = Mandanism::where(['category' => 'online_articles', 'id' => $id])->first();

        return response([
            'status' => true,
            'message' => 'Our Culture Detail.',
            'data' => new MandanismDetailResource($data)
        ], 201);
    }

    public function OnlineVideos(Request $request)
    {
        $data = Mandanism::where(['category' => 'online_videos', 'status' => 'active'])->get();

        return response([
            'status' => true,
            'message' => 'Our Culture List.',
            'data' => MandanismResource::collection($data)
        ], 201);
    }

    public function OnlineVideoDetail($id)
    {
        $data = Mandanism::where(['category' => 'online_videos', 'id' => $id])->first();

        return response([
            'status' => true,
            'message' => 'Our Culture Detail.',
            'data' => new MandanismDetailResource($data)
        ], 201);
    }
}
