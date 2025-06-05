<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Funeral;
use Validator;
use Auth;

class FuneralController extends Controller
{
    public function Funeral(Request $request){
        $data['coffin'] = [
            ["id"=>1,"name"=>"Richmond Teak Matt"],
            ["id"=>2,"name"=>"Richmond Teak Matt Oversized $150"],
            ["id"=>3,"name"=>"Richmond Teak Gloss $250"],
            ["id"=>4,"name"=>"Richmond Teak Gloss Oversized $400"],
            ["id"=>5,"name"=>"Richmond Red Cedar Gloss $250"],
            ["id"=>6,"name"=>"Richmond Red Cedar Gloss Oversized $400"],
            ["id"=>7,"name"=>"Richmond Rosewood Gloss $250"],
            ["id"=>8,"name"=>"Richmond Rosewood Gloss Oversized $400"],
            ["id"=>9,"name"=>"Richmond White Gloss $250"],
            ["id"=>10,"name"=>"Richmond White Gloss Oversized $400]"]
        ]; 
        $data['coffin_flower'] = [
            ['id'=>1,"name"=>"Not Required "],
            ['id'=>2,"name"=>"Standard White Seasonal Mix $350"]
        ];
        $data['transfers'] = [
            ['id'=>1,"name"=>"Deceased staying at home (No Charge)"],
            ['id'=>2,"name"=>"Deceased transferring from Home $520"],
            ['id'=>3,"name"=>"Deceased transferring from Hospital $275"],
            ['id'=>4,"name"=>"Deceased transferring from Coroner $275"],
            ['id'=>5,"name"=>"Deceased transferring from Nursing Home $520"]
        ];
        $data['cremation'] = [
            ['id'=>1,"name"=>"Weekday"],
            ['id'=>2,"name"=>"Saturday: $880"],
            ['id'=>3,"name"=>"Sunday: $1320"]
        ];

        return response([
            'status' => true,
            'message' => 'Fetched',
            'data' => $data
        ],201);
    }

    public function FuneralPost(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
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

        $data = new Funeral;
        // $data->coffin = $request->coffin;
        // $data->coffin_flower = $request->coffin_flower;
        // $data->transfers = $request->transfers;
        // $data->cremation = $request->cremation;
        $data->salutation = $request->salutation;
        $data->name = $request->name;
        $data->family_name = $request->family_name;
        $data->dob = date('Y-m-d',strtotime($request->dob));
        $data->dod = date('Y-m-d',strtotime($request->dod));
        $data->register_address = $request->register_address;
        $data->pass_away = $request->pass_away;
        $data->body_now = $request->body_now;
        $data->kins_salutation = $request->kins_salutation;
        $data->kins_name = $request->kins_name;
        $data->kins_family_name = $request->kins_family_name;
        $data->kins_address = $request->kins_address;
        $data->kins_mobile = $request->kins_mobile;
        $data->kins_email = $request->kins_email;
        $data->relationship = $request->relationship;

        if ($request->hasFile('identity'))
        {
            $destinationPath = 'uploads/';
            $file = $request->file('identity');
            $file_name = time().''.$file->getClientOriginalName();
            $file->move('public/'.$destinationPath, $file_name);
            $data->identity = $destinationPath.''.$file_name;
        }

        if ($request->hasFile('kins_identity'))
        {
            $destinationPath = 'uploads/';
            $file = $request->file('kins_identity');
            $file_name = time().''.$file->getClientOriginalName();
            $file->move('public/'.$destinationPath, $file_name);
            $data->kins_identity = $destinationPath.''.$file_name;
        }

        if ($request->hasFile('signature'))
        {
            $destinationPath = 'uploads/';
            $file = $request->file('signature');
            $file_name = time().''.$file->getClientOriginalName();
            $file->move('public/'.$destinationPath, $file_name);
            $data->signature = $destinationPath.''.$file_name;
        }
        
        $data->save();

        return response([
            'status' => true,
            'message' => 'Added.',
            'data' => []
        ],201);
    }
}
