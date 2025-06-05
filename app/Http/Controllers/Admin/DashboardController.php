<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        $data['users'] = User::orderBy('id','desc')->take(5)->get();
        $data['total_users'] = User::count();
        $data['total_orders'] = Order::count();
        $data['total_donation'] = 0;
        return view('admin.dashboard',['data'=>$data]);
    }
}
