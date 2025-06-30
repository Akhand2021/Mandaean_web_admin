<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Address;
use App\Models\Product;
use Auth;
use Validator;

class CartController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/add-to-cart",
     *     summary="Add item to cart",
     *     tags={"Cart"},
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id","color","size"},
     *             @OA\Property(property="product_id", type="integer", example=1),
     *             @OA\Property(property="color", type="string", example="red"),
     *             @OA\Property(property="size", type="string", example="M")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Item added.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or not enough quantity."
     *     )
     * )
     */
    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'color' => 'required',
            'size' => 'required'
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
        $cart = Cart::where(['user_id' => $id, 'status' => 'active'])->first();
        if (!$cart) {
            $address = Address::where(['user_id' => $id, 'is_primary' => 'yes'])->first();
            $cart = Cart::create([
                'user_id' => $id,
                'address_id' => $address ? $address->id : null
            ]);

            $product = Product::find($request->product_id);
            $cartDetail = CartDetail::where(['cart_id' => $cart->id, 'product_id' => $request->product_id, 'size' => $request->size, 'color' => $request->color])->first();
            if ($cartDetail) {
                $cartQty = $cartDetail->qty + 1;
                $inventory = $product->inventory;
                if ($cartQty > $inventory) {
                    return response([
                        'status' => false,
                        'message' => 'Product quantity is not enough.',
                        'data' => []
                    ], 422);
                }
                CartDetail::where('id', $cartDetail->id)->update(['qty' => 1]);
            } else {
                $inventory = $product->inventory;
                if ($inventory < 1) {
                    return response([
                        'status' => false,
                        'message' => 'Product quantity is not enough.',
                        'data' => []
                    ], 422);
                }
                $cartDetail = CartDetail::create(['cart_id' => $cart->id, 'product_id' => $request->product_id, 'price' => $product->price, 'size' => $request->size, 'color' => $request->color, 'qty' => 1]);
            }
        } else {
            $product = Product::find($request->product_id);
            $cartDetail = CartDetail::where(['cart_id' => $cart->id, 'product_id' => $request->product_id, 'size' => $request->size, 'color' => $request->color])->first();
            if ($cartDetail) {
                $cartQty = $cartDetail->qty + 1;
                $inventory = $product->inventory;
                if ($cartQty > $inventory) {
                    return response([
                        'status' => false,
                        'message' => 'Product quantity is not enough.',
                        'data' => []
                    ], 422);
                }
                CartDetail::where('id', $cartDetail->id)->update(['qty' => 1]);
            } else {
                $inventory = $product->inventory;
                if ($inventory < 1) {
                    return response([
                        'status' => false,
                        'message' => 'Product quantity is not enough.',
                        'data' => []
                    ], 422);
                }
                $cartDetail = CartDetail::create(['cart_id' => $cart->id, 'product_id' => $request->product_id, 'price' => $product->price, 'size' => $request->size, 'color' => $request->color, 'qty' => 1]);
            }
        }

        $amount = 0;
        $items = CartDetail::where('cart_id', $cartDetail->cart_id)->get();
        foreach ($items as $item) {
            $amount += $item->price * $item->qty;
        }
        Cart::where(['id' => $cartDetail->cart_id])->update(['total_amount' => $amount]);

        return response([
            'status' => true,
            'message' => 'Item added.',
            'data' => $cart
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/get-cart",
     *     summary="Get current user's cart",
     *     tags={"Cart"},
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=201,
     *         description="Cart Data.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Cart Empty."
     *     )
     * )
     */
    public function getCart(Request $request)
    {
        $id = Auth::id();
        $cart = Cart::with('address')
            ->with(['detail' => function ($query) {
                $query->with(['product' => function ($q) {
                    $q->with('images');
                }])->with(['color', 'size']);
            }])
            ->withCount('detail as items')
            ->where(['user_id' => $id, 'status' => 'active'])
            ->first();

        if ($cart) {
            foreach ($cart->detail as $key => $value) {
                foreach ($value->product->images as $k => $val) {
                    $val->image = url('/') . '/' . $val->image;
                }
                $value->delivery_date = 'Delivery by ' . date('D M d');
            }
            return response([
                'status' => true,
                'message' => 'Cart Data.',
                'data' => $cart
            ], 201);
        } else {
            return response([
                'status' => false,
                'message' => 'Cart Empty.',
                'data' => []
            ], 422);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/update-item",
     *     summary="Update item quantity in cart",
     *     tags={"Cart"},
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"item_id","type"},
     *             @OA\Property(property="item_id", type="integer", example=1),
     *             @OA\Property(property="type", type="string", example="add", description="add or remove")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Item updated.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or not enough quantity."
     *     )
     * )
     */
    public function updateItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'type' => 'required'
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
        $cartDetail = CartDetail::find($request->item_id);
        if ($request->type == 'add') {
            $qty = $cartDetail->qty + 1;
            $product = Product::find($cartDetail->product_id);
            $inventory = $product->inventory;
            if ($qty > $inventory) {
                return response([
                    'status' => false,
                    'message' => 'Product quantity is not enough.',
                    'data' => []
                ], 422);
            }
            CartDetail::where('id', $request->item_id)->update(['qty' => $qty]);
        } else {
            if ($cartDetail->qty == 1) {
                CartDetail::where('id', $request->item_id)->delete();
            } else {
                $qty = $cartDetail->qty - 1;
                CartDetail::where('id', $request->item_id)->update(['qty' => $qty]);
            }
        }

        $detail = CartDetail::find($request->item_id);

        $amount = 0;
        $items = CartDetail::where('cart_id', $detail->cart_id)->get();
        foreach ($items as $item) {
            $amount += $item->price * $item->qty;
        }
        Cart::where(['id' => $detail->cart_id])->update(['total_amount' => $amount]);

        return response([
            'status' => true,
            'message' => 'Item updated.',
            'data' => $detail
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/delete-item",
     *     summary="Delete item(s) from cart",
     *     tags={"Cart"},
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"item_id"},
     *             @OA\Property(property="item_id", type="string", example="[1,2,3]", description="JSON array of item IDs as string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Item deleted.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error."
     *     )
     * )
     */
    public function deleteItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
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
        $items_ids = json_decode($request->item_id, true);
        foreach ($items_ids as $key => $value) {
            CartDetail::where('id', $value)->delete();
        }

        $cart = Cart::where(['user_id' => $id, 'status' => 'active'])->first();
        $items = CartDetail::where('cart_id', $cart->id)->get();
        if (count($items) > 0) {
            $amount = 0;
            foreach ($items as $item) {
                $amount += $item->price * $item->qty;
            }
            Cart::where(['id' => $cart->id])->update(['total_amount' => $amount]);
        } else {
            Cart::where(['id' => $cart->id])->delete();
        }

        return response([
            'status' => true,
            'message' => 'Item deleted.',
            'data' => []
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/user-address",
     *     summary="Add or update user address for cart",
     *     tags={"Cart"},
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","code","mobile_no","first_address","second_address","state","city","postal_code"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="code", type="string", example="US"),
     *             @OA\Property(property="mobile_no", type="string", example="1234567890"),
     *             @OA\Property(property="first_address", type="string", example="123 Main St"),
     *             @OA\Property(property="second_address", type="string", example="Apt 4B"),
     *             @OA\Property(property="state", type="string", example="California"),
     *             @OA\Property(property="city", type="string", example="Los Angeles"),
     *             @OA\Property(property="postal_code", type="string", example="90001")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Address added.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error."
     *     )
     * )
     */
    public function userAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required',
            'mobile_no' => 'required',
            'first_address' => 'required',
            'second_address' => 'required',
            'state' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
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
        Address::where('user_id', $id)->update(['is_primary' => 'no']);
        $address = Address::updateOrCreate([
            'user_id' => $id,
            'code' => $request->code,
            'mobile_no' => $request->mobile_no,
            'first_address' => $request->first_address,
            'second_address' => $request->second_address,
            'state' => $request->state,
            'city' => $request->city,
            'postal_code' => $request->postal_code
        ], [
            'name' => $request->name,
            'is_primary' => 'yes'
        ]);

        Cart::where(['user_id' => $id, 'status' => 'active'])->update(['address_id' => $address->id]);

        return response([
            'status' => true,
            'message' => 'Address added.',
            'data' => $address
        ], 201);
    }
}
