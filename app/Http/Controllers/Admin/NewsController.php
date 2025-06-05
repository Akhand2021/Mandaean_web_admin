<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\LatestNews;
use Validator;
use Hash;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['filter'] = $request->filter;
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        $dataList = LatestNews::orderBy('id','desc');
        $search = $request->search;
        if($search){
            $dataList->where('title', 'LIKE', '%'.$search.'%')
                ->orWhere('group', 'LIKE', '%'.$search.'%');
        }
        $dataList = $dataList->get();
        
        if ($request->ajax()) {
            return DataTables::of($dataList)
                ->addColumn('country', function($row){
                    if($row->country){
                        return strtoupper($row->country);
                    }else{
                        return 'N/A';
                    }
                })
                ->addColumn('action', function($row){
                    $editimg = asset('/').'public/assets/images/edit-round-line.png';
                    $btn = '<a href="'.route('news.edit',$row->id).'" title="Edit"><label class="badge badge-gradient-dark">Edit</label></a> ';
                    $delimg = asset('/').'public/assets/images/dlt-icon.png';
                    $btn .= '<a href="" data-bs-toggle="modal" data-bs-target="#staticBackdrop3" class="deldata" id="'.$row->id.'" title="Delete" onclick=\'setData('.$row->id.',"'.route('news.destroy',$row->id).'");\'><label class="badge badge-danger">Delete</label></a>';
                    return $btn;
                })
                ->rawColumns(['country', 'action'])
                ->make(true);
        }

        return view('admin.news.index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        return view('admin.news.create',['data'=>$data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:200',
            'group' => 'required',
            'description' => 'required',
            'image' => 'required',
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
            $news = new LatestNews();
            $news['title'] = $request->title;
            $news['group'] = $request->group;
            $news['description'] = $request->description;
            $news['country'] = $request->country;
            $news['date'] = $request->date;
            $news['ar_title'] = $request->ar_title;
            $news['ar_group'] = $request->ar_group;
            $news['ar_description'] = $request->ar_description;
            $news['pe_title'] = $request->pe_title;
            $news['pe_group'] = $request->pe_group;
            $news['pe_description'] = $request->pe_description;
            if ($request->hasFile('image'))
            {
                $destinationPath = 'uploads/';
                $file = $request->file('image');
                $file_name = time().''.$file->getClientOriginalName();
                $file->move($destinationPath , $file_name);
                $imageName = $destinationPath.''.$file_name;
                $news['image'] = $imageName;
            }
            if ($request->hasFile('docs'))
            {
                $destinationPath = 'uploads/';
                $file = $request->file('docs');
                $file_name = time().''.$file->getClientOriginalName();
                $file->move($destinationPath , $file_name);
                $imageName = $destinationPath.''.$file_name;
                $news['docs'] = $imageName;
            }
            $news->save();
            return redirect('news')->with('message', 'Record Added!');
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
        $data['news'] = LatestNews::find($id);
        return view('admin.news.edit',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:200',
            'group' => 'required',
            'description' => 'required',
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
            $news = LatestNews::find($id);
            $news['title'] = $request->title;
            $news['group'] = $request->group;
            $news['description'] = $request->description;
            $news['country'] = $request->country;
            $news['date'] = $request->date;
            $news['ar_title'] = $request->ar_title;
            $news['ar_group'] = $request->ar_group;
            $news['ar_description'] = $request->ar_description;
            $news['pe_title'] = $request->pe_title;
            $news['pe_group'] = $request->pe_group;
            $news['pe_description'] = $request->pe_description;
            if ($request->hasFile('image'))
            {
                $destinationPath = 'uploads/';
                $file = $request->file('image');
                $file_name = time().''.$file->getClientOriginalName();
                $file->move($destinationPath , $file_name);
                $imageName = $destinationPath.''.$file_name;
                $news['image'] = $imageName;
            }
            if ($request->hasFile('docs'))
            {
                $destinationPath = 'uploads/';
                $file = $request->file('docs');
                $file_name = time().''.$file->getClientOriginalName();
                $file->move($destinationPath , $file_name);
                $imageName = $destinationPath.''.$file_name;
                $news['docs'] = $imageName;
            }
            $news->save();
            return redirect('news')->with('message', 'Record Updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return LatestNews::where('id',$id)->delete();
    }
}
