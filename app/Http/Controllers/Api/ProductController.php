<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response    
     * This method retrieves a list of products based on search and filter criteria.
     * It returns a JSON response with the status, message, and product data.
     * @OA\Get(
     *     path="/api/product-list",
     *     tags={"Product"},
     *     summary="Product List",  
     *     description="Get a list of products with optional search and filter",
     *    security={{"apiKey":{}}, {"bearerAuth":{}}},
     *    @OA\Parameter(
     *        name="search",
     *       in="query",
     *       description="Search term for product name or price",
     *      required=false,
     *      @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *       name="filter",
     *      in="query",
     * 
     *  description="Filter products by price (1: low to high, 2: high to low)",
     *     required=false,
     *     @OA\Schema(type="integer", enum={1, 2, 3, 4})
     *  ),
     *  @OA\Response(
     *      response=200,
     *     description="Successful operation",
     *    @OA\JsonContent(
     *       type="object",
     *      @OA\Property(property="status", type="boolean", example=true),
     *      @OA\Property(property="message", type="string", example="Product List."),
     *   )
     * )
     * )
     */
    public function ProductList(Request $request)
    {
        $search = $request->search;
        $filter = $request->filter;
        $data = Product::with(['images', 'colors', 'sizes', 'brands'])
            ->where('status', 'active');
        if ($search) {
            $data = $data->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('price', 'LIKE', '%' . $search . '%');
        }
        if ($filter) {
            if ($filter == 3) {
                $data = $data->orderBy('price', 'asc');
            } else if ($filter == 4) {
                $data = $data->orderBy('price', 'desc');
            }
        }
        $data = $data->get();

        foreach ($data as $key => $value) {
            foreach ($value->images as $k => $val) {
                $val->image =  $val->image ? url('/') . '/' . $val->image : null;
            }
        }
        return response([
            'status' => true,
            'message' => 'Product List.',
            'data' => $data
        ], 201);
    }
    /**
     * @OA\Get(
     *     path="/api/product-detail/{id}",
     *     tags={"Product"},
     *     description="Get Product Detail", 
     *     summary="Product Detail",
     *     operationId="productDetail",  
     *     security={{"apiKey":{}}, {"bearerAuth":{}}},
     *      @OA\Parameter(
     *         name="id",
     *         in="path", 
     *         description="ID of the product",
     *         required=true,
     *         @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *        response=200,
     *       description="Successful operation",
     *      @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="status", type="boolean", example=true),
     *        @OA\Property(property="message", type="string", example="Product Detail."),
     *        @OA\Property(property="data", type="object"),
     *        @OA\Property(property="id", type="integer", example=1),
     *        @OA\Property(property="name", type="string", example="Product Name"),
     *        @OA\Property(property="price", type="number", format="float", example=99.99),
     *        @OA\Property(property="description", type="string", example="Product description here."),
     *        @OA\Property(property="is_in_cart", type="boolean", example=false),
     *        @OA\Property(property="cart_quantity", type="integer", example=0),
     *      )
     * )
     *    
     * )
     * 
     */
    public function ProductDetail(Request $request, $id)
    {
        $user =  Auth::id();

        $data = Product::with(['images', 'colors', 'sizes', 'brands'])->where('status', 'active')->find($id);
        if (!$data) {
            return response([
                'status' => false,
                'message' => 'Product not found.',
            ], 404);
        }
        foreach ($data->images as $k => $val) {
            $val->image = url('/') . '/' . $val->image;
        }
        $is_in_cart = false;
        $cart_quantity = 0;
        if ($user) {
            $cart = \App\Models\Cart::where('user_id', $user)->where('status', 'active')->first();

            if ($cart) {
                $cart_detail = $cart->detail()->where('product_id', $id)->first();
                if ($cart_detail) {
                    $is_in_cart = true;
                    $cart_quantity = $cart_detail->qty;
                }
            }
        }
        $data->is_in_cart = $is_in_cart;
        $data->cart_quantity = $cart_quantity;
        return response([
            'status' => true,
            'message' => 'Product Detail.',
            'data' => $data
        ], 201);
    }
}
