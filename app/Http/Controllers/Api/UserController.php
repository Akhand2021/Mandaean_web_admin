<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function profile(Request $request){
        $id = Auth::id();
        $user = User::find($id);

        if($user){
            $user->gender = ($user->gender)?ucfirst($user->gender):NULL;
            $user->profile = ($user->profile)?url('/').'/public/'.$user->profile:NULL;
            return response([
                'status'=>true,
                'message'=>'User profile data.',
                'data'=>$user
            ],201);
        }else{
            return response([
                'status'=>false,
                'message'=>'User not found.',
                'data'=>[]
            ],422);
        }
    }

    public function updateProfile(Request $request){
        $id = Auth::id();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$id,
            'country_code' => 'required',
            'mobile_no' => 'required|unique:users,mobile_no,'.$id,
            // 'password' => 'required',
            // 'gender' => 'required',
            // 'dob' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response([
                'status'=>false,
                'message'=>$error,
                'data'=>[]
            ],422);
        }

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if($request->mobile_no){
            $user->country_code = '+'.$request->country_code;
        }
        if($request->mobile_no){
            $user->mobile_no = $request->mobile_no;
        }
        // $user->password = Hash::make($request->password);
        // $user->gender = $request->gender;
        // $user->dob = $request->dob;

        if ($request->hasFile('profile'))
        {
            $destinationPath = 'uploads/';
            $file = $request->file('profile');
            $file_name = time().''.$file->getClientOriginalName();
            $file->move('public/'.$destinationPath, $file_name);
            $user->profile = $destinationPath.''.$file_name;
        }
        $user->save();
        $user->profile = $user->profile?url('/').'/public/'.$user->profile:NULL;

        return response([
            'status'=>true,
            'message'=>'User profile data updated.',
            'data'=>$user
        ],201);
    }

    public function changePassword(Request $request){
        $id = Auth::id();
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response([
                'status'=>false,
                'message'=>$error,
                'data'=>[]
            ],422);
        }

        $oldPass = $request->old_password;
        $newPass = $request->new_password;
        $user = User::find($id);
        
        if (!$user) {
            return response([
                'status'=>false,
                'message'=>'User not found.',
                'data'=>[]
            ],422);
        }
        if (Hash::check($oldPass, $user->password)) {
            $user->password = Hash::make($newPass);
            $user->save();
            
            return response([
                'status'=>true,
                'message'=>'Password changed successfully.',
                'data'=>$user
            ],201);
        }else{
            return response([
                'status'=>false,
                'message'=>'Old password does not match.',
                'data'=>[]
            ],422);
        }
    }

    public function deleteAccount(Request $request){
        $id = Auth::id();
        $user = User::where(['id'=>$id])->first();
        if($user){
            User::where('email',$user->email)->delete();
            return response([
                'status'=>true,
                'message'=>'Account Deleted.',
                'data'=>[]
            ],201);
        }else{
            return response([
                'status'=>false,
                'message'=>'User not found.',
                'data'=>[]
            ],422);
        }
    }
}
