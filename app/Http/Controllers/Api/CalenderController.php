<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReligiousOccasion;
use App\Models\ChooseCalender;
use App\Models\Event;
use App\Models\EventReminder;
use App\Models\Melvashe;
use App\Http\Resources\EventResource;
use Validator;
use Auth;

class CalenderController extends Controller
{
    public function ReligiousOccasions(Request $request){
        $validator = Validator::make($request->all(), [
            'year' => 'required',
            'date_type' => 'required',
            'occasion_type' => 'required'
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

        $data = ReligiousOccasion::select('id','date','occasion')
            ->where('year', $request->year)
            ->where('date_type', $request->date_type)
            ->where('occasion_type', $request->occasion_type)
            ->get();

        if(count($data)<1){
            if($request->date_type=='Solar'){
                $data = ReligiousOccasion::select('id','date','occasion')
                    ->where('year', 1300)
                    ->where('date_type', $request->date_type)
                    ->where('occasion_type', $request->occasion_type)
                    ->get();
            }else{
                $data = ReligiousOccasion::select('id','date','occasion')
                    ->where('year', 2021)
                    ->where('date_type', $request->date_type)
                    ->where('occasion_type', $request->occasion_type)
                    ->get();
            }
            
            foreach ($data as $key => $value) {
                $value->date = date('Y-m-d',strtotime($request->year.'-'.date('m',strtotime($value->date)).'-'.date('d',strtotime($value->date))));
            }
        }
        
        return response([
            'status'=>true,
            'message'=>'Occasion List.',
            'data'=>$data
        ],201);
    
    }

    public function ChooseCalender(Request $request){
        $validator = Validator::make($request->all(), [
            'first_display' => 'required',
            'second_display' => 'required',
            'third_display' => 'required'
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

        $occasion = ChooseCalender::where('user_id',$id)->first();
        if($occasion){
            ChooseCalender::where(['user_id' => $id])->update([
                'first_display' => $request->first_display,
                'second_display' => $request->second_display,
                'third_display' => $request->third_display
            ]);
            $message = 'Record Updated.';
        }else{
            ChooseCalender::create([
                'user_id' => $id,
                'first_display' => $request->first_display,
                'second_display' => $request->second_display,
                'third_display' => $request->third_display
            ]);
            $message = 'Record Added.';
        }

        return response([
            'status' => true,
            'message' => $message,
            'data' => []
        ],201);
    }

    public function SetEventReminder(Request $request){
        $validator = Validator::make($request->all(), [
            'date_type' => 'required',
            'set_before_reminder' => 'required'
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response([
                'status' => false,
                'message' => $error,
                'data' => []
            ],422);
        }

        $eventType = json_decode($request->event_type,true);
        if (count($eventType)<1) {
            return response([
                'status'=>false,
                'field'=>'event_type',
                'message'=>'Select at least one event type for reminder.',
                'data'=>[]
            ],422);
        }
        $id = Auth::id();

        $reminder = EventReminder::where('user_id',$id)->first();
        if($reminder){
            EventReminder::where(['user_id' => $id])->update([
                'date_type' => $request->date_type,
                'event_type' => json_encode(array_unique($eventType)),
                'set_before_reminder' => $request->set_before_reminder
            ]);
        }else{
            EventReminder::create([
                'user_id' => $id,
                'date_type' => $request->date_type,
                'event_type' => json_encode(array_unique($eventType)),
                'set_before_reminder' => $request->set_before_reminder
            ]); 
        }

        return response([
            'status' => true,
            'message' => 'Reminder set successfully.',
            'data' => []
        ],201);
    }

    public function DeleteAllReminder(Request $request){
        $id = Auth::id();

        $reminder = EventReminder::where('user_id',$id)->delete();
        return response([
            'status' => true,
            'message' => 'All reminders deleted.',
            'data' => []
        ],201);
    }

    public function CalenderList(Request $request){
        $date = $request->date;
        $allDates = ReligiousOccasion::select('date','occasion','occasion_type')->where('year',date('Y'))->where(['status'=>'active'])->get();
        $data = ReligiousOccasion::select('id','date','occasion','occasion_type')->where(['date'=>$date, 'status'=>'active'])->get();

        return response([
            'status' => true,
            'message' => 'All calender list.',
            'data' => $data,
            'allDates' => $allDates
        ],201);
    }

    public function Melvashe(){

        $data['melvashe'] = [
            ['id'=>1,'name'=>'Hawa'],
            ['id'=>2,'name'=>'Sharat'],
            ['id'=>3,'name'=>'Yasman'],
            ['id'=>4,'name'=>'Modl-lal'],
            ['id'=>5,'name'=>'Anhar'],
            ['id'=>6,'name'=>'Mahnash'],
            ['id'=>7,'name'=>'Simet'],
            ['id'=>8,'name'=>'Hawa Simet'],
            ['id'=>9,'name'=>'Sharat Simet'],
            ['id'=>10,'name'=>'Mamani'],
            ['id'=>11,'name'=>'Hawa Mamani'],
            ['id'=>12,'name'=>'Maliha'],
            ['id'=>13,'name'=>'Narges']
        ];

        $data['months'] = [
            ['id'=>1,'name'=>'Embra'],
            ['id'=>2,'name'=>'Taura'],
            ['id'=>3,'name'=>'Selmi'],
            ['id'=>4,'name'=>'Sartana'],
            ['id'=>5,'name'=>'Aria'],
            ['id'=>6,'name'=>'Shombolta'],
            ['id'=>7,'name'=>'Panja Day 1'],
            ['id'=>8,'name'=>'Panja Day 2'],
            ['id'=>9,'name'=>'Panja Day 3'],
            ['id'=>10,'name'=>'Panja Day 4'],
            ['id'=>11,'name'=>'Panja Day 5'],
            ['id'=>12,'name'=>'Qaina'],
            ['id'=>13,'name'=>'Arqawa'],
            ['id'=>14,'name'=>'Hatia'],
            ['id'=>15,'name'=>'Gadia'],
            ['id'=>16,'name'=>'Daula'],
            ['id'=>17,'name'=>'Nuna']
        ];

        return response([
            'status' => true,
            'message' => 'Melvashe list.',
            'data' => $data
        ],201);
    }

    public function MelvasheFind(Request $request){
        $validator = Validator::make($request->all(), [
            'mother_name' => 'required',
            'birth_month' => 'required',
            'gender' => 'required',
            'time_type' => 'required',
            'time' => 'required'
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response([
                'status' => false,
                'message' => $error,
                'data' => []
            ],422);
        }

        $mother_name = $request->mother_name;
        $birth_month = $request->birth_month;
        $gender = $request->gender;
        $time_type = $request->time_type;
        $time = $request->time;

        $data = Melvashe::select('id','talea','first_melvashe_name','second_melvashe_name')
                    ->where('mother_name', $mother_name)
                    ->where('birth_month', $birth_month)
                    ->where('gender', $gender)
                    ->where('time_type', $time_type)
                    ->where('from', $time)
                    ->first();

        if($data){
            return response([
                'status' => true,
                'message' => 'Data fetched.',
                'data' => $data
            ],201);
        }else{
            $data = [
                "id" => 1,
                "talea" => "Sheep",
                "first_melvashe_name" => "Maliha _Path_ Hawa",
                "second_melvashe_name" => "Narges _Path_ Hawa"
            ];
            return response([
                'status' => true,
                'message' => 'Data fetched.',
                'data' => $data
            ],201);
        }
    }
}
