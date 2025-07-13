<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Melvashe;
use Illuminate\Support\Facades\Validator;


class MelvasheController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/melvashe",
     *     tags={"Melvashe"},
     *     summary="Get all Melvashe records",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="Accept",
     *         in="header",
     *         required=false,
     *         description="Force response as JSON",
     *         @OA\Schema(type="string", default="application/json")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of Melvashe records."
     *     )
     * )
     */
    public function index()
    {
        $melvashe = Melvashe::all();
        return response(['status' => true, 'data' => $melvashe], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/melvashe",
     *     tags={"Melvashe"},
     *     summary="Create a new Melvashe record",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"mother_name","birth_month","gender","time_type","from","talea","first_melvashe_name"},
     *             @OA\Property(property="mother_name", type="string", example="Mother Name"),
     *             @OA\Property(property="birth_month", type="string", example="January"),
     *             @OA\Property(property="gender", type="string", example="Male"),
     *             @OA\Property(property="time_type", type="string", example="Morning"),
     *             @OA\Property(property="from", type="string", example="2025-01-01"),
     *             @OA\Property(property="to", type="string", example="2025-12-31"),
     *             @OA\Property(property="talea", type="string", example="Some Talea"),
     *             @OA\Property(property="first_melvashe_name", type="string", example="First Name"),
     *             @OA\Property(property="second_melvashe_name", type="string", example="Second Name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Melvashe created."
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mother_name' => 'required|string|max:255',
            'birth_month' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'time_type' => 'required|string|max:255',
            'from' => 'required|string|max:255',
            'to' => 'nullable|string|max:255',
            'talea' => 'required|string|max:255',
            'first_melvashe_name' => 'required|string|max:255',
            'second_melvashe_name' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return response(['status' => false, 'message' => $validator->errors()->first()], 422);
        }
        $melvashe = Melvashe::create($request->only([
            'mother_name',
            'birth_month',
            'gender',
            'time_type',
            'from',
            'to',
            'talea',
            'first_melvashe_name',
            'second_melvashe_name'
        ]));
        return response(['status' => true, 'data' => $melvashe], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/melvashe/{id}",
     *     tags={"Melvashe"},
     *     summary="Get a single Melvashe record",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Melvashe record."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found."
     *     )
     * )
     */
    public function show($id)
    {
        $melvashe = Melvashe::find($id);
        if (!$melvashe) {
            return response(['status' => false, 'message' => 'Not found.'], 404);
        }
        return response(['status' => true, 'data' => $melvashe], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/melvashe/{id}",
     *     tags={"Melvashe"},
     *     summary="Update a Melvashe record",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"mother_name","birth_month","gender","time_type","from","talea","first_melvashe_name"},
     *             @OA\Property(property="mother_name", type="string", example="Mother Name"),
     *             @OA\Property(property="birth_month", type="string", example="January"),
     *             @OA\Property(property="gender", type="string", example="Male"),
     *             @OA\Property(property="time_type", type="string", example="Morning"),
     *             @OA\Property(property="from", type="string", example="2025-01-01"),
     *             @OA\Property(property="to", type="string", example="2025-12-31"),
     *             @OA\Property(property="talea", type="string", example="Some Talea"),
     *             @OA\Property(property="first_melvashe_name", type="string", example="First Name"),
     *             @OA\Property(property="second_melvashe_name", type="string", example="Second Name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Melvashe updated."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found."
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $melvashe = Melvashe::find($id);
        if (!$melvashe) {
            return response(['status' => false, 'message' => 'Not found.'], 404);
        }
        $validator = Validator::make($request->all(), [
            'mother_name' => 'required|string|max:255',
            'birth_month' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'time_type' => 'required|string|max:255',
            'from' => 'required|string|max:255',
            'to' => 'nullable|string|max:255',
            'talea' => 'required|string|max:255',
            'first_melvashe_name' => 'required|string|max:255',
            'second_melvashe_name' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return response(['status' => false, 'message' => $validator->errors()->first()], 422);
        }
        $melvashe->update($request->only([
            'mother_name',
            'birth_month',
            'gender',
            'time_type',
            'from',
            'to',
            'talea',
            'first_melvashe_name',
            'second_melvashe_name'
        ]));
        return response(['status' => true, 'data' => $melvashe], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/melvashe/{id}",
     *     tags={"Melvashe"},
     *     summary="Delete a Melvashe record",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Melvashe deleted."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found."
     *     )
     * )
     */
    public function destroy($id)
    {
        $melvashe = Melvashe::find($id);
        if (!$melvashe) {
            return response(['status' => false, 'message' => 'Not found.'], 404);
        }
        $melvashe->delete();
        return response(['status' => true, 'message' => 'Deleted.'], 200);
    }
}
