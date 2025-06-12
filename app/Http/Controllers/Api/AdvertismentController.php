<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Advertisment;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Auth;

class AdvertismentController extends Controller
{
    public function PlaceAdvertisment(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'code' => 'required',
            'phone' => 'required',
            'subject' => 'required',
            'description' => 'required',
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

        $data = Advertisment::create([
            'user_id' => $id,
            'name' => $request->name,
            'email' => $request->email,
            'code' => $request->code,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'description' => $request->description,
        ]);

        return response([
            'status' => true,
            'message' => 'Success.',
            'data' => $data
        ],201);
    }

    public function AdvertismentList(Request $request){
        $id = Auth::id();

        $data = Advertisment::get();

        return response([
            'status' => true,
            'message' => 'List.',
            'data' => $data
        ],201);
    }
}
