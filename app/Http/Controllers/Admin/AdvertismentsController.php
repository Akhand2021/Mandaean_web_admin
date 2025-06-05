<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Advertisment;
use Validator;
use Hash;

class AdvertismentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['filter'] = $request->filter;
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        $dataList = Advertisment::orderBy('id','desc');
        $search = $request->search;
        if($search){
            $dataList->where('name', 'LIKE', '%'.$search.'%')
                ->orWhere('email', 'LIKE', '%'.$search.'%');
        }
        $dataList = $dataList->get();
        
        if ($request->ajax()) {
            return DataTables::of($dataList)
                ->addColumn('action', function($row){
                    $editimg = asset('/').'public/assets/images/edit-round-line.png';
                    $btn = '<a href="'.route('advertisment.edit',$row->id).'" title="Edit"><label class="badge badge-gradient-dark">Edit</label></a> ';
                    $delimg = asset('/').'public/assets/images/dlt-icon.png';
                    $btn .= '<a href="" data-bs-toggle="modal" data-bs-target="#staticBackdrop3" class="deldata" id="'.$row->id.'" title="Delete" onclick=\'setData('.$row->id.',"'.route('delete-advertisment').'");\'><label class="badge badge-danger">Delete</label></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.advertisment.index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        return view('admin.advertisment.create',['data'=>$data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:200',
            'email' => 'required',
            'code' => 'required',
            'phone' => 'required',
            'subject' => 'required',
            'description' => 'required'
        ],[
            'ar_title.required' => 'The title field is required.',
            'ar_group.required' => 'The group field is required.',
            'ar_description.required' => 'The description field is required.',
            'pe_title.required' => 'The title field is required.',
            'pe_group.required' => 'The group field is required.',
            'pe_description.required' => 'The description field is required.',
        ]);
 
        if ($validator->fails())
        {
            $messages = $validator->messages();
            return back()->withInput()->withErrors($messages);
        }else{
            $password = rand_string(6);
            $advertisment = new Advertisment();
            $advertisment['name'] = $request->name;
            $advertisment['email'] = $request->email;
            $advertisment['code'] = $request->code;
            $advertisment['phone'] = $request->phone;
            $advertisment['subject'] = $request->subject;
            $advertisment['description'] = $request->description;
            $advertisment->save();
            return redirect('advertisment')->with('message', 'Record Added!');
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
        $data['advertisment'] = Advertisment::find($id);
        return view('admin.advertisment.edit',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:200',
            'email' => 'required',
            'code' => 'required',
            'phone' => 'required',
            'subject' => 'required',
            'description' => 'required'
        ],[
            'ar_title.required' => 'The title field is required.',
            'ar_group.required' => 'The group field is required.',
            'ar_description.required' => 'The description field is required.',
            'pe_title.required' => 'The title field is required.',
            'pe_group.required' => 'The group field is required.',
            'pe_description.required' => 'The description field is required.',
        ]);
 
        if ($validator->fails())
        {
            $messages = $validator->messages();
            return back()->withInput()->withErrors($messages);
        }else{
            $advertisment = Advertisment::find($id);
            $advertisment['name'] = $request->name;
            $advertisment['email'] = $request->email;
            $advertisment['code'] = $request->code;
            $advertisment['phone'] = $request->phone;
            $advertisment['subject'] = $request->subject;
            $advertisment['description'] = $request->description;
            $advertisment->save();
            return redirect('advertisment')->with('message', 'Record Updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteAdvertisment(Request $request)
    {
        $id = $request->id;
        return Advertisment::where('id',$id)->delete();
    }
}
