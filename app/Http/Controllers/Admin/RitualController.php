<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Ritual;
use Validator;
use Hash;

class RitualController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['filter'] = $request->filter;
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        $dataList = Ritual::orderBy('id','desc');
        $search = $request->search;
        if($search){
            $dataList->where('title', 'LIKE', '%'.$search.'%')
                ->orWhere('description', 'LIKE', '%'.$search.'%');
        }
        $dataList = $dataList->get();
        
        if ($request->ajax()) {
            return DataTables::of($dataList)
                ->editColumn('description', function($row){
                    return $row->description;
                })
                ->addColumn('action', function($row){
                    $editimg = asset('/').'public/assets/images/edit-round-line.png';
                    $btn = '<a href="'.route('ritual.edit',$row->id).'" title="Edit"><label class="badge badge-gradient-dark">Edit</label></a> ';
                    $delimg = asset('/').'public/assets/images/dlt-icon.png';
                    $btn .= '<a href="" data-bs-toggle="modal" data-bs-target="#staticBackdrop3" class="deldata" id="'.$row->id.'" title="Delete" onclick=\'setData('.$row->id.',"'.route('ritual.destroy',$row->id).'");\'><label class="badge badge-danger">Delete</label></a>';
                    return $btn;
                })
                ->rawColumns(['description','action'])
                ->make(true);
        }

        return view('admin.ritual.index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        return view('admin.ritual.create',['data'=>$data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:200',
            'description' => 'required',
            // 'ar_title' => 'required|max:200',
            // 'ar_description' => 'required',
            // 'pe_title' => 'required|max:200',
            // 'pe_description' => 'required',
        ],[
            'ar_title.required' => 'The title field is required.',
            'ar_description.required' => 'The description field is required.',
            'pe_title.required' => 'The title field is required.',
            'pe_description.required' => 'The description field is required.',
        ]);
 
        if ($validator->fails())
        {
            $messages = $validator->messages();
            return back()->withInput()->withErrors($messages);
        }else{
            $password = rand_string(6);
            $ritual = new Ritual();
            if ($request->hasFile('docs'))
            {
                $destinationPath = 'uploads/';
                $file = $request->file('docs');
                $file_name = time().''.$file->getClientOriginalName();
                $file->move($destinationPath , $file_name);
                $imageName = $destinationPath.''.$file_name;
                $ritual['docs'] = $imageName;
            }
            $ritual['title'] = $request->title;
            $ritual['description'] = $request->description;
            $ritual['ar_title'] = $request->ar_title;
            $ritual['ar_description'] = $request->ar_description;
            $ritual['pe_title'] = $request->pe_title;
            $ritual['pe_description'] = $request->pe_description;
            $ritual->save();
            return redirect('ritual')->with('message', 'Record Added!');
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
        $data['ritual'] = Ritual::find($id);
        return view('admin.ritual.edit',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:200',
            'description' => 'required',
            // 'ar_title' => 'required|max:200',
            // 'ar_description' => 'required',
            // 'pe_title' => 'required|max:200',
            // 'pe_description' => 'required',
        ],[
            'ar_title.required' => 'The title field is required.',
            'ar_description.required' => 'The description field is required.',
            'pe_title.required' => 'The title field is required.',
            'pe_description.required' => 'The description field is required.',
        ]);
 
        if ($validator->fails())
        {
            $messages = $validator->messages();
            return back()->withInput()->withErrors($messages);
        }else{
            $ritual = Ritual::find($id);
            if ($request->hasFile('docs'))
            {
                $destinationPath = 'uploads/';
                $file = $request->file('docs');
                $file_name = time().''.$file->getClientOriginalName();
                $file->move($destinationPath , $file_name);
                $imageName = $destinationPath.''.$file_name;
                $ritual['docs'] = $imageName;
            }
            $ritual['title'] = $request->title;
            $ritual['description'] = $request->description;
            $ritual['ar_title'] = $request->ar_title;
            $ritual['ar_description'] = $request->ar_description;
            $ritual['pe_title'] = $request->pe_title;
            $ritual['pe_description'] = $request->pe_description;
            $ritual->save();
            return redirect('ritual')->with('message', 'Record Updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Ritual::where('id',$id)->delete();
    }
}
