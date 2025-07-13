<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/order-history",
     *     tags={"Order"},
     *     summary="Get Order History",
     *     description="Retrieve the authenticated user's order history.",
     *     security={{"apiKey":{}},{"bearerAuth": {}}}, 
     *    @OA\Response(
     *        response=201,
     *       description="Order history fetched successfully.",
     *        @OA\JsonContent(
     *           @OA\Property(property="status", type="boolean", example=true),
     *          @OA\Property(property="message", type="string", example="Order Data."),
     *          @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *        )
     *    ),
     *   @OA\Response(
     *       response=422,
     *      description="No orders found.",
     *      @OA\JsonContent(
     *          @OA\Property(property="status", type="boolean", example=false),
     *         @OA\Property(property="message", type="string", example="No Orders."),
     *        @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *       )
     *  )
     * )
     */
    public function orderHistory(Request $request)
    {
        $id = Auth::id();
        $orders = Order::with('address')
            ->with(['detail' => function ($query) {
                $query->with(['product' => function ($q) {
                    $q->with('images');
                }])->with(['color', 'size']);
            }])
            ->withCount('detail as items')
            ->where('user_id', $id)
            ->get();

        if (count($orders) > 0) {
            foreach ($orders as $key => $value) {
                foreach ($value->detail as $val) {
                    foreach ($val->product->images as $image) {
                        $imgPath = str_replace(url('/') . '/', '', $image->image);
                        $image->image = url('/') . '/' . $imgPath;
                    }
                }
            }
            return response([
                'status' => true,
                'message' => 'Order Data.',
                'data' => $orders
            ], 201);
        } else {
            return response([
                'status' => false,
                'message' => 'No Orders.',
                'data' => []
            ], 422);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/order-detail/{orderid}",
     *     tags={"Order"},
     *     summary="Get Order Detail",
     *     description="Retrieve the details of a specific order by order ID.",
     *     security={{"apiKey":{}},{"bearerAuth": {}}}, 
     *    @OA\Parameter(
     *        name="orderid",
     *        in="path",
     *        required=true,
     *        @OA\Schema(type="integer")
     *    ),
     *    @OA\Response(
     *        response=201,
     *       description="Order detail fetched successfully.",
     *        @OA\JsonContent(
     *           @OA\Property(property="status", type="boolean", example=true),
     *          @OA\Property(property="message", type="string", example="Order Data."),
     *          @OA\Property(property="data", type="object")
     *        )
     *    ),
     *   @OA\Response(
     *       response=422,
     *      description="No orders found.",
     *      @OA\JsonContent(
     *          @OA\Property(property="status", type="boolean", example=false),
     *         @OA\Property(property="message", type="string", example="No Orders."),
     *        @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *       )
     *  )
     * )
     */
    public function orderDetail(Request $request, $orderid)
    {
        $id = Auth::id();
        $order = Order::with('address')
            ->with(['detail' => function ($query) {
                $query->with(['product' => function ($q) {
                    $q->with('images');
                }])->with(['color', 'size']);
            }])
            ->withCount('detail as items')
            ->where('id', $orderid)
            ->first();

        if ($order) {
            foreach ($order->detail as $val) {
                foreach ($val->product->images as $image) {
                    $imgPath = str_replace(url('/') . '/', '', $image->image);
                    $image->image = url('/') . '/' . $imgPath;
                }
            }

            return response([
                'status' => true,
                'message' => 'Order Data.',
                'data' => $order
            ], 201);
        } else {
            return response([
                'status' => false,
                'message' => 'No Orders.',
                'data' => []
            ], 422);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/create-order",
     *     tags={"Order"},
     *     summary="Create Order",
     *     description="Create a new order from the user's cart. Payment is handled by the app developer.",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="address_id", type="integer", example=1, description="User's address ID for delivery")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Order created."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or cart empty.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Cart is empty."),
     *             @OA\Property(property="data", type="object", example={})
     *         )
     *     )
     * )
     */
    public function createOrder(Request $request)
    {
        $id = Auth::id();
        $validator = Validator::make($request->all(), [
            'address_id' => 'required|exists:user_addresses,id'
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response([
                'status' => false,
                'message' => $error,
                'data' => []
            ], 422);
        }
        $cart = \App\Models\Cart::with('detail')->where(['user_id' => $id, 'status' => 'active'])->first();
        if (!$cart || $cart->detail->isEmpty()) {
            return response([
                'status' => false,
                'message' => 'Cart is empty.',
                'data' => []
            ], 422);
        }
        $order = Order::create([
            'order_number' => uniqid('ORD'),
            'transaction_id' => $request->transaction_id ?? null,
            'user_id' => $id,
            'address_id' => $request->address_id,
            'total_amount' => $cart->total_amount,
            'status' => 'pending',
        ]);

        foreach ($cart->detail as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'price' => $item->price,
                'qty' => $item->qty,
                'size' => $item->size,
                'color' => $item->color,
            ]);
        }
        $cart->status = 'ordered';
        $cart->save();
        return response([
            'status' => true,
            'message' => 'Order created.',
            'data' => [
                'order_number' => $order->order_number,
                'transaction_id' => $order->transaction_id,
                'user_id' => $order->user_id,
                'address_id' => $order->address_id,
                'total_amount' => $order->total_amount,
                'status' => $order->status,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at
            ]
        ], 201);
    }
}
