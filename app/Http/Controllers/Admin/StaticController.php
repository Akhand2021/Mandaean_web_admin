<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StaticContent;
use DataTables;
use Validator;

class StaticController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['filter'] = $request->filter;
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        $dataList = StaticContent::orderBy('id', 'desc');
        $search = $request->search;
        if ($search) {
            $dataList->where('title', 'LIKE', '%' . $search . '%')
                ->orWhere('content', 'LIKE', '%' . $search . '%');
        }
        $dataList = $dataList->get();

        if ($request->ajax()) {
            return DataTables::of($dataList)
                ->addColumn('image', function ($row) {
                    if ($row->image) {
                        return '<img src="' . asset($row->image) . '" style="max-width:60px;max-height:60px;" />';
                    }
                    return '';
                })
                ->addColumn('action', function ($row) {
                    $editimg = asset('/') . 'public/assets/images/edit-round-line.png';
                    $btn = '<a href="' . route('static-content.edit', $row->id) . '" title="Edit"><label class="badge badge-gradient-dark">Edit</label></a> ';
                    return $btn;
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }

        return view('admin.static.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.static.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:200',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ], [
            'title.required' => 'The title field is required.',
            'content.required' => 'The content field is required.'
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();
            return back()->withInput()->withErrors($messages);
        } else {
            $data = $request->only(['slug', 'title', 'content', 'ar_title', 'ar_content', 'pe_title', 'pe_content']);
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/static'), $imageName);
                $data['image'] = 'uploads/static/' . $imageName;
            }
            StaticContent::create($data);
            return redirect('static-content')->with('message', 'Record Created!');
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
        $data['static'] = StaticContent::find($id);
        return view('admin.static.edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:200',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ], [
            'title.required' => 'The title field is required.',
            'content.required' => 'The content field is required.'
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();
            return back()->withInput()->withErrors($messages);
        } else {
            $static = StaticContent::find($id);
            $static['title'] = $request->title;
            $static['content'] = $request->content;
            $static['ar_title'] = $request->ar_title;
            $static['ar_content'] = $request->ar_content;
            $static['pe_title'] = $request->pe_title;
            $static['pe_content'] = $request->pe_content;
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($static->image && file_exists(public_path($static->image))) {
                    unlink(public_path($static->image));
                }
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/static'), $imageName);
                $static['image'] = 'uploads/static/' . $imageName;
            }
            $static->save();
            return redirect('static-content')->with('message', 'Record Updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $static = StaticContent::find($id);
        if ($static) {
            if ($static->image && file_exists(public_path($static->image))) {
                unlink(public_path($static->image));
            }
            $static->delete();
            return redirect('static-content')->with('message', 'Record Deleted!');
        }
        return redirect('static-content')->with('error', 'Record not found!');
    }
}
