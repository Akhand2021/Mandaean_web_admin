<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Baptism;
use App\Models\BaptismVenue;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Auth;

class BaptismController extends Controller
{
    public function BaptismVenue(Request $request){
        $data = BaptismVenue::select('id','name','status')->where('status','active')->get();

        return response([
            'status' => true,
            'message' => 'Fetched.',
            'data' => $data
        ],201);
    }

    public function BookBaptism(Request $request){
        $validator = Validator::make($request->all(), [
            'venue' => 'required',
            'name' => 'required',
            'email' => 'required',
            'date' => 'required',
            'code' => 'required',
            'phone' => 'required'
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

        $data = Baptism::create([
            'user_id' => $id,
            'venue' => $request->venue,
            'name' => $request->name,
            'date' => $request->date,
            'email' => $request->email,
            'code' => $request->code,
            'phone' => $request->phone
        ]);

        return response([
            'status' => true,
            'message' => 'Booked successfully.',
            'data' => $data
        ],201);
    }
}
