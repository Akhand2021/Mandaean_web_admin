<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;

class LoginController extends Controller
{
    public function Login(){
        $data['sort_name'] = 'Sandeep';
        if(session()->has('adminuser'))
        {
            return redirect('dashboard');
        }
        $data['useremail'] = isset($_COOKIE['useremail'])?$_COOKIE['useremail']:'';
        return view('auth.login',['data'=>$data]);
    }

    public function index(Request $request)
    {
        // dd($request->all());
        if(strlen(trim($request->input('email'))) == 0 && strlen(trim($request->input('password'))) == 0){
            return redirect()->back()->with('error','Email and Password are required.');
        }elseif(strlen(trim($request->input('email'))) == 0){
            return redirect()->back()->with('error','Email field is required.');
        }elseif(strlen(trim($request->input('password'))) == 0){
            return redirect()->back()->with('error','Password field is required.');
        }
        $table = Admin::where('email',$request->input('email'))->first();

        if($table)
        {
            $check = $table->password;
            $password=Hash::check($request->input('password'),$check);
            if($password)
            {
                session()->put('adminuser',$table);
                return redirect('dashboard');
            }
            else
            {
                return redirect()->back()->with('error','Credential does not matched.');
            }
        }
        else
        {
            return redirect()->back()->with('error','Credential does not matched.');
        }

    }

    public function logout()
    {
        session()->forget('adminuser');
        return redirect('login');
    }

    public function deleteAccount()
    {
        return view('auth.delete');
    }

    public function deleteAccountPost(Request $request)
    {
        if(strlen(trim($request->input('email'))) == 0 && strlen(trim($request->input('password'))) == 0){
            return redirect()->back()->with('error','Email and Password are required.');
        }elseif(strlen(trim($request->input('email'))) == 0){
            return redirect()->back()->with('error','Email field is required.');
        }elseif(strlen(trim($request->input('password'))) == 0){
            return redirect()->back()->with('error','Password field is required.');
        }
        $table = User::where('email',$request->input('email'))->first();

        if($table)
        {
            $check = $table->password;
            $password=Hash::check($request->input('password'),$check);
            if($password)
            {
                User::where('id',$table->id)->delete();
                return redirect()->back()->with('message','Account deleted successfully.');
            }
            else
            {
                return redirect()->back()->with('error','Credential does not matched.');
            }
        }
        else
        {
            return redirect()->back()->with('error','Credential does not matched.');
        }
    }
}
