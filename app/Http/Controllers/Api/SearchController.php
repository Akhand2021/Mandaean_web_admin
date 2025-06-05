<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Mandanism;
use App\Models\RecentSearch;
use Validator;
use Auth;

class SearchController extends Controller
{
    public function Search(Request $request){
        $validator = Validator::make($request->all(), [
            'search' => 'required'
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response([
                'status' => false,
                'message' => $error,
                'data' => []
            ],422);
        }

        $id = Auth::id();
        $searchArray = [];
        $search = $request->search;
        $product = Product::where('status','active')
            ->where('name', 'LIKE', '%'.$search.'%')
            ->get();

        foreach ($product as $key => $value) {
            $array['id'] = $value->id;
            $array['name'] = $value->name;
            $array['section'] = 'product';
            array_push($searchArray, $array);
        }

        $mandanism = Mandanism::where('title', 'LIKE', '%'.$search.'%')
                // ->orWhere('group', 'LIKE', '%'.$search.'%')
                // ->orWhere('description', 'LIKE', '%'.$search.'%')
                ->get();

        foreach ($mandanism as $key1 => $value1) {
            $array['id'] = $value1->id;
            $array['name'] = $value1->title;
            $array['section'] = 'mandanism category';
            array_push($searchArray, $array);
        }

        $recent = RecentSearch::select('search_text')->where(['user_id'=>$id])->groupBy('search_text')->take(5)->get();

        RecentSearch::create([
            'user_id' => $id,
            'search_text' => $search,
        ]);

        if(count($searchArray)>0){
            return response([
                'status' => true,
                'message' => 'Data fetched.',
                'data' => $searchArray,
                'recent_search' => $recent
            ],201);
        }else{
            return response([
                'status' => false,
                'message' => 'No search found.',
                'data' => [],
                'recent_search' => $recent
            ],422);
        }
    }
}
