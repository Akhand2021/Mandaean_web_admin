<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Auth;

class NotificationController extends Controller
{
    public function NotificationList(Request $request){
        $id = Auth::id();

        $data = Notification::select('id','sender_id','receiver_id','device_type','title','message','message_type','status')->where('receiver_id',$id)->get();

        if(count($data)>0){
            return response([
                'status' => true,
                'message' => 'Notification List.',
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

    public function ReadNotification(Request $request){
        $id = Auth::id();
        $notifyId = $request->id;
        $data = Notification::where(['id'=>$notifyId])->update(['status'=>'read']);

        return response([
            'status' => true,
            'message' => 'Status updated.',
            'data' => []
        ],201);
    }

    public function DeleteNotification(Request $request){
        $id = Auth::id();
        $notifyId = $request->id;
        
        Notification::where(['id'=>$notifyId])->delete();

        return response([
            'status' => true,
            'message' => 'Deleted.',
            'data' => []
        ],201);
    }
}
