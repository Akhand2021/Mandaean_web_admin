<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Mandanism;
use Validator;
use Hash;
use App\Helper\Helper;

class MandanismController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['filter'] = $request->filter;
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        $dataList = Mandanism::orderBy('id','desc');
        $search = $request->search;
        if($search){
            $dataList->where('title', 'LIKE', '%'.$search.'%')
                ->orWhere('group', 'LIKE', '%'.$search.'%')
                ->orWhere('description', 'LIKE', '%'.$search.'%');
        }
        $dataList = $dataList->get();
        
        if ($request->ajax()) {
            return DataTables::of($dataList)
                ->editColumn('description', function($row){
                    return $row->description;
                })
                ->editColumn('category', function($row){
                    return ucfirst(str_replace("_", ' ', $row->category));
                })
                ->addColumn('action', function($row){
                    $editimg = asset('/').'public/assets/images/edit-round-line.png';
                    $btn = '<a href="'.route('mandanism.edit',$row->id).'" title="Edit"><label class="badge badge-gradient-dark">Edit</label></a> ';
                    $delimg = asset('/').'public/assets/images/dlt-icon.png';
                    $btn .= '<a href="" data-bs-toggle="modal" data-bs-target="#staticBackdrop3" class="deldata" id="'.$row->id.'" title="Delete" onclick=\'setData('.$row->id.',"'.route('mandanism.destroy',$row->id).'");\'><label class="badge badge-danger">Delete</label></a>';
                    return $btn;
                })
                ->rawColumns(['description','action'])
                ->make(true);
        }

        return view('admin.mandanism.index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        return view('admin.mandanism.create',['data'=>$data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|max:200',
            // 'group' => 'required',
            // 'description' => 'required',
            // 'image' => 'required',
            // 'date' => 'required',
            // 'ar_title' => 'required|max:200',
            // 'ar_group' => 'required',
            // 'ar_description' => 'required',
            // 'pe_title' => 'required|max:200',
            // 'pe_group' => 'required',
            // 'pe_description' => 'required',
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
            $mandanism = new Mandanism();
            $mandanism['title'] = $request->title;
            $mandanism['category'] = $request->category;
            $mandanism['group'] = $request->group;
            $mandanism['description'] = $request->description;
            $mandanism['date'] = $request->date;
            $mandanism['ar_title'] = $request->ar_title;
            $mandanism['ar_group'] = $request->ar_group;
            $mandanism['ar_description'] = $request->ar_description;
            $mandanism['pe_title'] = $request->pe_title;
            $mandanism['pe_group'] = $request->pe_group;
            $mandanism['pe_description'] = $request->pe_description;
            $mandanism['online_link'] = $request->online_link;
            if ($request->hasFile('image'))
            {
                $mandanism['image'] = upload_file_common($request->file('image'));
            }
            if ($request->hasFile('docs'))
            {
                $mandanism['docs'] = upload_file_common($request->file('docs'));
            }
            $mandanism->save();
            return redirect('mandanism')->with('message', 'Record Added!');
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
        $data['mandanism'] = Mandanism::find($id);
        return view('admin.mandanism.edit',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|max:200',
            // 'group' => 'required',
            // 'description' => 'required',
            // 'date' => 'required',
            // 'ar_title' => 'required|max:200',
            // 'ar_group' => 'required',
            // 'ar_description' => 'required',
            // 'pe_title' => 'required|max:200',
            // 'pe_group' => 'required',
            // 'pe_description' => 'required',
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
            $mandanism = Mandanism::find($id);
            $mandanism['title'] = $request->title;
            $mandanism['category'] = $request->category;
            $mandanism['group'] = $request->group;
            $mandanism['description'] = $request->description;
            $mandanism['date'] = $request->date;
            $mandanism['ar_title'] = $request->ar_title;
            $mandanism['ar_group'] = $request->ar_group;
            $mandanism['ar_description'] = $request->ar_description;
            $mandanism['pe_title'] = $request->pe_title;
            $mandanism['pe_group'] = $request->pe_group;
            $mandanism['pe_description'] = $request->pe_description;
            $mandanism['online_link'] = $request->online_link;
            if ($request->hasFile('image'))
            {
                $mandanism['image'] = upload_file_common($request->file('image'));
            }
            if ($request->hasFile('docs'))
            {
                $mandanism['docs'] = upload_file_common($request->file('docs'));
            }
            $mandanism->save();
            return redirect('mandanism')->with('message', 'Record Updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Mandanism::where('id',$id)->delete();
    }
}
