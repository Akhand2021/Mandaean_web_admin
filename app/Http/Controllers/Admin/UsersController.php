<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use Validator;
use Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['filter'] = $request->filter;
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        $dataList = User::orderBy('id','desc');
        $search = $request->search;
        if($search){
            $dataList->where('name', 'LIKE', '%'.$search.'%')
                ->orWhere('email', 'LIKE', '%'.$search.'%')
                ->orWhere('phone', 'LIKE', '%'.$search.'%');
        }
        $dataList = $dataList->get();
        
        if ($request->ajax()) {
            return DataTables::of($dataList)
                ->editColumn('profile', function($row){
                    if($row->profile){
                        return '<img src="'.url('/').'/'.$row->profile.'" alt="image" />';
                    }else{
                        return '<img src="'.url('/').'/assets/images/profile.png" alt="image" />';
                    }
                })
                // ->editColumn('phone', function($row){
                //     return $row->country_code.''.$row->mobile_no;
                // })
                ->addColumn('action', function($row){
                    $editimg = asset('/').'public/assets/images/edit-round-line.png';
                    $btn = '<a href="'.route('users.edit',$row->id).'" title="Edit"><label class="badge badge-gradient-dark">Edit</label></a> ';
                    $delimg = asset('/').'public/assets/images/dlt-icon.png';
                    $btn .= '<a href="" data-bs-toggle="modal" data-bs-target="#staticBackdrop3" class="deldata" id="'.$row->id.'" title="Delete" onclick=\'setData('.$row->id.',"'.route('users.destroy',$row->id).'");\'><label class="badge badge-danger">Delete</label></a>';
                    return $btn;
                })
                ->rawColumns(['profile','action'])
                ->make(true);
        }

        return view('admin.users.index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        return view('admin.users.create',['data'=>$data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:200',
            'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i|unique:users',
            // 'country_code' => 'required',
            // 'mobile_no' => 'required',
            'gender' => 'required',
            'photo' => 'required'
        ]);
 
        if ($validator->fails())
        {
            $messages = $validator->messages();
            return back()->withInput()->withErrors($messages);
        }else{
            $password = rand_string(6);
            $user = new User();
            $user['name'] = $request->name;
            $user['email'] = $request->email;
            $user['gender'] = $request->gender;
            $user['password'] = Hash::make($password);
            // $user['country_code'] = $request->country_code;
            // $user['mobile_no'] = $request->mobile_no;
            $user['email_verified_at'] = date('Y-m-d H:i:s');

            if ($request->hasFile('photo'))
            {
                // $destinationPath = 'public/uploads/';
                $destinationPath = 'uploads/';
                $file = $request->file('photo');
                $file_name = time().''.$file->getClientOriginalName();
                $file->move($destinationPath , $file_name);
                $imageName = $destinationPath.''.$file_name;
                $user['profile'] = $imageName;
            }
    
            $user->save();
            return redirect('users')->with('message', 'Record Added!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        $data['user'] = User::find($id);
        return view('admin.users.edit',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:200',
            'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i|unique:users,email,'.$id.',id',
            // 'country_code' => 'required',
            // 'mobile_no' => 'required',
            'gender' => 'required'
        ]);
 
        if ($validator->fails())
        {
            $messages = $validator->messages();
            return back()->withInput()->withErrors($messages);
        }else{
            $user = User::find($id);
            $user['name'] = $request->name;
            $user['email'] = $request->email;
            $user['gender'] = $request->gender;
            // $user['country_code'] = $request->country_code;
            // $user['mobile_no'] = $request->mobile_no;

            if ($request->hasFile('photo'))
            {
                // $destinationPath = 'public/uploads/';
                $destinationPath = 'uploads/';
                $file = $request->file('photo');
                $file_name = time().''.$file->getClientOriginalName();
                $file->move($destinationPath , $file_name);
                $imageName = $destinationPath.''.$file_name;
                $user['profile'] = $imageName;
            }
    
            $user->save();
            return redirect('users')->with('message', 'Record Updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return User::where('id',$id)->delete();
    }
}
