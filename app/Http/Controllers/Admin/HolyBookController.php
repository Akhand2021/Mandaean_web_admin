<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\HolyBook;
use Validator;
use Hash;
use App\Helper\Helper;

class HolyBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['filter'] = $request->filter;
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        $dataList = HolyBook::orderBy('id', 'desc');
        $search = $request->search;
        if ($search) {
            $dataList->where('title', 'LIKE', '%' . $search . '%')
                ->orWhere('description', 'LIKE', '%' . $search . '%');
        }
        $dataList = $dataList->get();
        if ($request->ajax()) {
            return DataTables::of($dataList)
                ->addColumn('image', function ($row) {
                    if ($row->type == 'holy') {
                        $url = url('/') . '/' . $row->image;
                        return '<img src="' . $url . '">';
                    } else {
                        $url = url('/') . '/' . $row->other_image;
                        return '<img src="' . $url . '">';
                    }
                })
                ->editColumn('title', function ($row) {
                    if ($row->type == 'holy') {
                        return $row->title;
                    } else {
                        return $row->other_title;
                    }
                })
                ->addColumn('action', function ($row) {
                    $editimg = asset('/') . 'public/assets/images/edit-round-line.png';
                    $btn = '<a href="' . route('books.edit', $row->id) . '" title="Edit"><label class="badge badge-gradient-dark">Edit</label></a> ';
                    $delimg = asset('/') . 'public/assets/images/dlt-icon.png';
                    $btn .= '<a href="" data-bs-toggle="modal" data-bs-target="#staticBackdrop3" class="deldata" id="' . $row->id . '" title="Delete" onclick=\'setData(' . $row->id . ',"' . route('books.destroy', $row->id) . '");\'><label class="badge badge-danger">Delete</label></a>';
                    return $btn;
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }
        return view('admin.holybooks.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        return view('admin.holybooks.create', ['data' => $data]);
    }

    /**
     * Handle file upload and return the stored path
     */
    private function uploadFile($file, $destinationPath = 'uploads/')
    {
        $originalName = $file->getClientOriginalName();
        $cleanName = preg_replace('/\s+/', '_', $originalName);
        $file_name = time() . '_' . $cleanName;
        $file->move($destinationPath, $file_name);
        return $destinationPath . $file_name;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'title' => 'required|max:200',
        //     'description' => 'required',
        //     'image' => 'required',
        //     'url' => 'required',
        //     'ar_title' => 'required|max:200',
        //     'ar_description' => 'required',
        //     'pe_title' => 'required|max:200',
        //     'pe_description' => 'required',
        // ],[
        //     'ar_title.required' => 'The title field is required.',
        //     'ar_description.required' => 'The description field is required.',
        //     'pe_title.required' => 'The title field is required.',
        //     'pe_description.required' => 'The description field is required.',
        // ]);

        // if ($validator->fails())
        // {
        //     $messages = $validator->messages();
        //     return back()->withInput()->withErrors($messages);
        // }else{
        $books = new HolyBook();
        $books['type'] = $request->type;
        $books['author'] = $request->author;

        if ($request->type == 'holy') {
            $books['title'] = $request->title;
            $books['description'] = $request->description;
            $books['ar_title'] = $request->ar_title;
            $books['ar_description'] = $request->ar_description;
            $books['pe_title'] = $request->pe_title;
            $books['pe_description'] = $request->pe_description;
            if ($request->hasFile('image')) {
                $books['image'] = upload_file_common($request->file('image'));
            }
            if ($request->hasFile('url')) {
                $books['url'] = upload_file_common($request->file('url'));
            }
        } else {
            $books['other_title'] = $request->other_title;
            $books['other_description'] = $request->other_description;
            $books['other_ar_title'] = $request->other_ar_title;
            $books['other_ar_description'] = $request->other_ar_description;
            $books['other_pe_title'] = $request->other_pe_title;
            $books['other_pe_description'] = $request->other_pe_description;
            if ($request->hasFile('other_image')) {
                $books['other_image'] = upload_file_common($request->file('other_image'));
            }
            if ($request->hasFile('other_url')) {
                $books['other_url'] = upload_file_common($request->file('other_url'));
            }
        }
        $books->save();
        return redirect('books')->with('message', 'Record Added!');
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
        $data['books'] = HolyBook::find($id);
        return view('admin.holybooks.edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $validator = Validator::make($request->all(), [
        //     'title' => 'required|max:200',
        //     'description' => 'required',
        //     'ar_title' => 'required|max:200',
        //     'ar_description' => 'required',
        //     'pe_title' => 'required|max:200',
        //     'pe_description' => 'required',
        // ],[
        //     'ar_title.required' => 'The title field is required.',
        //     'ar_description.required' => 'The description field is required.',
        //     'pe_title.required' => 'The title field is required.',
        //     'pe_description.required' => 'The description field is required.',
        // ]);

        // if ($validator->fails())
        // {
        //     $messages = $validator->messages();
        //     return back()->withInput()->withErrors($messages);
        // }else{
        $books = HolyBook::find($id);
        $books['type'] = $request->type;
        $books['author'] = $request->author;

        if ($request->type == 'holy') {
            $books['title'] = $request->title;
            $books['description'] = $request->description;
            $books['ar_title'] = $request->ar_title;
            $books['ar_description'] = $request->ar_description;
            $books['pe_title'] = $request->pe_title;
            $books['pe_description'] = $request->pe_description;
            if ($request->hasFile('image')) {
                $books['image'] = upload_file_common($request->file('image'));
            }
            if ($request->hasFile('url')) {
                $books['url'] = upload_file_common($request->file('url'));
            }
        } else {
            $books['other_title'] = $request->other_title;
            $books['other_description'] = $request->other_description;
            $books['other_ar_title'] = $request->other_ar_title;
            $books['other_ar_description'] = $request->other_ar_description;
            $books['other_pe_title'] = $request->other_pe_title;
            $books['other_pe_description'] = $request->other_pe_description;
            if ($request->hasFile('other_image')) {
                $books['other_image'] = upload_file_common($request->file('other_image'));
            }
            if ($request->hasFile('other_url')) {
                $books['other_url'] = upload_file_common($request->file('other_url'));
            }
        }
        $books->save();
        return redirect('books')->with('message', 'Record Updated!');
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return HolyBook::where('id', $id)->delete();
    }
}
