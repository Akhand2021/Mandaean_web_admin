<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Donation;
use Auth;
use Validator;

class EventController extends Controller
{
    public function DonationEventList(Request $request){
        $id = Auth::id();

        $data = Event::where('status','active')->get();
        foreach ($data as $key => $value) {
            $data[$key]['date'] = date('D, M d',strtotime($value->date));
            $data[$key]['image'] = url('/').'/'.$value->image;
            $data[$key]['users_donated'] = 100;
        }
        if(count($data)>0){
            return response([
                'status' => true,
                'message' => 'Donation Event List.',
                'data' => $data
            ],201);
        }else{
            return response([
                'status' => false,
                'message' => 'No Record Found.',
                'data' => []
            ],422);
        }
    }

    public function EventDetail(Request $request, $eventId){
        $id = Auth::id();

        $data = Event::find($eventId);
        $data['date'] = date('D, M d',strtotime($data->date));
        $data['image'] = url('/').'/'.$data->image;
        $data['users_donated'] = 100;

        if($data){
            return response([
                'status' => true,
                'message' => 'Event Detail.',
                'data' => $data
            ],201);
        }else{
            return response([
                'status' => false,
                'message' => 'No Record Found.',
                'data' => []
            ],422);
        }
    }

    public function Donation(Request $request){
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
            'amount' => 'required'
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

        $data = Donation::create([
            'user_id' => $id,
            'event_id' => $request->event_id,
            'amount' => $request->amount
        ]);

        return response([
            'status' => true,
            'message' => 'Donated.',
            'data' => $data
        ],201);
    }
}
