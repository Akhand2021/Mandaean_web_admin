<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BaptismVenue;
use DataTables;
use Validator;

class BaptismVenueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['filter'] = $request->filter;
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        $dataList = BaptismVenue::orderBy('id','desc');
        $search = $request->search;
        if($search){
            $dataList->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('ar_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('pe_name', 'LIKE', '%'.$search.'%');
        }
        $dataList = $dataList->get();
        
        if ($request->ajax()) {
            return DataTables::of($dataList)
                ->addColumn('action', function($row){
                    $editimg = asset('/').'public/assets/images/edit-round-line.png';
                    $btn = '<a href="'.route('baptism-venue.edit',$row->id).'" title="Edit"><label class="badge badge-gradient-dark">Edit</label></a> ';
                    $delimg = asset('/').'public/assets/images/dlt-icon.png';
                    $btn .= '<a href="" data-bs-toggle="modal" data-bs-target="#staticBackdrop3" class="deldata" id="'.$row->id.'" title="Delete" onclick=\'setData('.$row->id.',"'.route('baptism-venue.destroy',$row->id).'");\'><label class="badge badge-danger">Delete</label></a>';
                    return $btn;
                })
                ->rawColumns(['image','action'])
                ->make(true);
        }

        return view('admin.baptismVenue.index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        return view('admin.baptismVenue.create',['data'=>$data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:150'
        ]);
 
        if ($validator->fails())
        {
            $messages = $validator->messages();
            return back()->withInput()->withErrors($messages);
        }else{
            $data = new BaptismVenue();
            $data['name'] = $request->name;
            $data['ar_name'] = $request->ar_name;
            $data['pe_name'] = $request->pe_name;
            $data->save();

            return redirect('baptism-venue')->with('message', 'Record Added!');
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
        $data['venue'] = BaptismVenue::find($id);
        return view('admin.baptismVenue.edit',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:150',
        ]);
 
        if ($validator->fails())
        {
            $messages = $validator->messages();
            return back()->withInput()->withErrors($messages);
        }else{
            $data = BaptismVenue::find($id);
            $data['name'] = $request->name;
            $data['ar_name'] = $request->ar_name;
            $data['pe_name'] = $request->pe_name;
            $data->save();

            return redirect('baptism-venue')->with('message', 'Record Updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        BaptismVenue::where('id',$id)->delete();
        
        return true;
    }
}
