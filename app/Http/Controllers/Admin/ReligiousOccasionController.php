<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ReligiousOccasion;
use Validator;
use Hash;

class ReligiousOccasionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['filter'] = $request->filter;
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        $dataList = ReligiousOccasion::orderBy('year','asc');
        $search = $request->search;
        if($search){
            $dataList->where('year', 'LIKE', '%'.$search.'%')
                ->orWhere('occasion', 'LIKE', '%'.$search.'%');
        }
        $dataList = $dataList->get();
        
        if ($request->ajax()) {
            return DataTables::of($dataList)
                ->addColumn('action', function($row){
                    $editimg = asset('/').'public/assets/images/edit-round-line.png';
                    $btn = '<a href="'.route('religious-occasion.edit',$row->id).'" title="Edit"><label class="badge badge-gradient-dark">Edit</label></a> ';
                    $delimg = asset('/').'public/assets/images/dlt-icon.png';
                    $btn .= '<a href="" data-bs-toggle="modal" data-bs-target="#staticBackdrop3" class="deldata" id="'.$row->id.'" title="Delete" onclick=\'setData('.$row->id.',"'.route('religious-occasion.destroy',$row->id).'");\'><label class="badge badge-danger">Delete</label></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.religious.index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        return view('admin.religious.create',['data'=>$data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'title' => 'required|max:200',
        //     'group' => 'required',
        //     'description' => 'required',
        //     'image' => 'required',
        // ],[
        //     'ar_title.required' => 'The title field is required.',
        //     'ar_group.required' => 'The group field is required.',
        //     'ar_description.required' => 'The description field is required.',
        //     'pe_title.required' => 'The title field is required.',
        //     'pe_group.required' => 'The group field is required.',
        //     'pe_description.required' => 'The description field is required.',
        // ]);
 
        // if ($validator->fails())
        // {
        //     $messages = $validator->messages();
        //     return back()->withInput()->withErrors($messages);
        // }else{
            $religious = new ReligiousOccasion();
            $religious['date'] = date('Y-m-d',strtotime($request->year.'-'.$request->month.'-'.$request->day));
            $religious['year'] = $request->year;
            $religious['date_type'] = $request->date_type;
            $religious['occasion'] = $request->occasion;
            $religious['occasion_type'] = $request->occasion_type;
            $religious->save();
            return redirect('religious-occasion')->with('message', 'Record Added!');
        // }
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
        $data['religious'] = ReligiousOccasion::find($id);
        return view('admin.religious.edit',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $validator = Validator::make($request->all(), [
        //     'title' => 'required|max:200',
        //     'group' => 'required',
        //     'description' => 'required',
        // ],[
        //     'ar_title.required' => 'The title field is required.',
        //     'ar_group.required' => 'The group field is required.',
        //     'ar_description.required' => 'The description field is required.',
        //     'pe_title.required' => 'The title field is required.',
        //     'pe_group.required' => 'The group field is required.',
        //     'pe_description.required' => 'The description field is required.',
        // ]);
 
        // if ($validator->fails())
        // {
        //     $messages = $validator->messages();
        //     return back()->withInput()->withErrors($messages);
        // }else{
            $religious = ReligiousOccasion::find($id);
            $religious['date'] = date('Y-m-d',strtotime($request->year.'-'.$request->month.'-'.$request->day));
            $religious['year'] = $request->year;
            $religious['date_type'] = $request->date_type;
            $religious['occasion'] = $request->occasion;
            $religious['occasion_type'] = $request->occasion_type;
            $religious->save();
            return redirect('religious-occasion')->with('message', 'Record Updated!');
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return ReligiousOccasion::where('id',$id)->delete();
    }
}
