<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use Validator;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/addresses",
     *      operationId="getAddressList",
     *      tags={"Address"},
     *      summary="Get list of addresses",
     *      description="Returns list of addresses",
     *      security={{"apiKey":{}}, {"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function index()
    {
        $addresses = Address::where('user_id', auth()->user()->id)->get();
        return response()->json([
            'status' => true,
            'message' => 'Address list.',
            'data' => $addresses
        ], 200);
    }

    /**
     * @OA\Post(
     *      path="/api/addresses",
     *      operationId="storeAddress",
     *      tags={"Address"},
     *      summary="Store new address",
     *      description="Returns address data",
     *      security={{"apiKey":{}}, {"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *             required={"name", "code", "mobile_no", "first_address", "city", "state", "postal_code"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *            @OA\Property(property="code", type="string", example="12345"),
     *           @OA\Property(property="mobile_no", type="string", example="+1234567890"),
     *           @OA\Property(property="first_address", type="string", example="123 Main St"),
     *          @OA\Property(property="second_address", type="string", example="Apt 4B"),
     *          @OA\Property(property="city", type="string", example="New York"),
     *         @OA\Property(property="state", type="string", example="NY"),
     *         @OA\Property(property="postal_code", type="string", example="10001"),
     *         @OA\Property(property="is_primary", type="boolean", example=true)
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function store(Request $request)
    {
        $userId =  Auth::id();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10',
            'mobile_no' => 'required|string|max:15',
            'first_address' => 'required|string|max:255',
            'second_address' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'is_primary' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $address = Address::create([
            'user_id' => $userId,
            'name' => $request->name,
            'code' => $request->code,
            'mobile_no' => $request->mobile_no,
            'first_address' => $request->first_address,
            'second_address' => $request->second_address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'is_primary' => $request->is_primary ?? false,
        ])->toArray();

        return response()->json([
            'status' => true,
            'message' => 'Address created successfully.',
            'data' => $address
        ], 201);
    }

    /**
     * @OA\Put(
     *      path="/api/addresses/{id}",
     *      operationId="updateAddress",
     *      tags={"Address"},
     *      summary="Update existing address",
     *      description="Returns updated address data",
     *      security={{"apiKey":{}}, {"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Address id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *             required={"name", "code", "mobile_no", "first_address", "city", "state", "postal_code"},
     *            @OA\Property(property="name", type="string", example="John Doe"),
     *           @OA\Property(property="code", type="string", example="12345"),
     *          @OA\Property(property="mobile_no", type="string", example="+1234567890"),
     *          @OA\Property(property="first_address", type="string", example="123 Main St"),
     *         @OA\Property(property="second_address", type="string", example="Apt 4B"),
     *         @OA\Property(property="city", type="string", example="New York"),
     *        @OA\Property(property="state", type="string", example="NY"),
     *       @OA\Property(property="postal_code", type="string", example="10001"),
     *        @OA\Property(property="is_primary", type="boolean", example=true)
     *          )
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function update(Request $request, Address $address)
    {
        if ($address->user_id != auth()->user()->id) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to update this address.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10',
            'mobile_no' => 'required|string|max:15',
            'first_address' => 'required|string|max:255',
            'second_address' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'is_primary' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $address->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Address updated successfully.',
            'data' => $address
        ], 202);
    }

    /**
     * @OA\Delete(
     *      path="/api/addresses/{id}",
     *      operationId="deleteAddress",
     *      tags={"Address"},
     *      summary="Delete existing address",
     *      description="Deletes a record and returns no content",
     *      security={{"apiKey":{}}, {"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Address id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function destroy(Address $address)
    {
        if ($address->user_id != auth()->user()->id) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to delete this address.',
            ], 403);
        }

        $address->delete();

        return response()->json([
            'status' => true,
            'message' => 'Address deleted successfully.'
        ], 204);
    }
}
