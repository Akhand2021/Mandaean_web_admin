<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use Auth;
use Validator;

class InquiryController extends Controller
{
    public function InquiryNow(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'ask_query' => 'required',
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

        $data = Inquiry::create([
            'user_id' => $id,
            'product_id' => $request->product_id,
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'query' => $request->ask_query,
            'status' => 'pending'
        ]);

        $email = $request->email;
        $name = $request->name;
        $template = 'emails.inquiry';
        $subject = 'Ask for query | Mandaean';
        $data = [
            'name' => $name,
            'email' => $email,
            'query' => $request->ask_query
        ];
        ___mail_sender('info@mandaean.world',$name,$template,$data,$subject);

        return response([
            'status' => true,
            'message' => 'Your query submitted.',
            'data' => $data
        ],201);
    }
}