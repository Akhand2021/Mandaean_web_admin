<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function ProductList(Request $request){
        $search = $request->search;
        $filter = $request->filter;
        $data = Product::with(['images','colors','sizes', 'brands'])
            ->where('status','active');
        if($search){
            $data = $data->where('name', 'LIKE', '%'.$search.'%')
                ->orWhere('price', 'LIKE', '%'.$search.'%');
        }
        if($filter){
            if($filter==3){
                $data = $data->orderBy('price','asc');
            }
            else if($filter==4){
                $data = $data->orderBy('price','desc');
            }
        }
        $data = $data->get();

        foreach ($data as $key => $value) {
            foreach ($value->images as $k => $val) {
                $val->image = url('/').'/public/'.$val->image;
            }
        }
        return response([
            'status' => true,
            'message' => 'Product List.',
            'data' => $data
        ],201);   
    }

    public function ProductDetail(Request $request, $id){
        $data = Product::with(['images','colors','sizes', 'brands'])->where('status','active')->find($id);
        foreach ($data->images as $k => $val) {
            $val->image = url('/').'/public/'.$val->image;
        }
        return response([
            'status' => true,
            'message' => 'Product Detail.',
            'data' => $data
        ],201);   
    }
}
