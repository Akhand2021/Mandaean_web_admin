<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Inquiry;
use Validator;

class InquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['filter'] = $request->filter;
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        $dataList = Inquiry::with('user')->orderBy('id','desc');
        $search = $request->search;
        if($search){
            $dataList->where('name', 'LIKE', '%'.$search.'%')
                ->orWhere('email', 'LIKE', '%'.$search.'%')
                ->orWhere('query', 'LIKE', '%'.$search.'%');
        }
        $dataList = $dataList->get();
        
        if ($request->ajax()) {
            return DataTables::of($dataList)
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('inquiry.reply',$row->id).'" title="Edit"><label class="badge badge-gradient-dark">Reply</label></a> ';
                    $btn .= '<a href="" data-bs-toggle="modal" data-bs-target="#staticBackdrop3" class="deldata" id="'.$row->id.'" title="Delete" onclick=\'setData('.$row->id.',"'.route('inquiry.destroy',$row->id).'");\'><label class="badge badge-danger">Delete</label></a>';
                    return $btn;
                })
                ->rawColumns(['image','action'])
                ->make(true);
        }

        return view('admin.inquiry.index',['data'=>$data]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Inquiry::where('id',$id)->delete();

        return true;
    }

    public function reply($id)
    {
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        $data['inquiry'] = Inquiry::with('user')->find($id);
        return view('admin.inquiry.reply',['data'=>$data]);
    }

    public function replyPost(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'reply_message' => 'required|max:200',
        ]);
 
        if ($validator->fails())
        {
            $messages = $validator->messages();
            return back()->withInput()->withErrors($messages);
        }
        else
        {
            $inquiry = Inquiry::find($id);
            $inquiry['status'] = 'replied';
            $inquiry['reply_message'] = $request->reply_message;
            $inquiry->save();

            return redirect('inquiry')->with('message', 'Replied!');
        }
    }
}