<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Prayer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PrayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['filter'] = $request->filter;
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        $dataList = Prayer::orderBy('id', 'desc');
        $search = $request->search;
        if ($search) {
            $dataList->where('title', 'LIKE', '%' . $search . '%')
                ->orWhere('subtitle', 'LIKE', '%' . $search . '%');
        }
        $dataList = $dataList->get();

        if ($request->ajax()) {
            return DataTables::of($dataList)
                ->editColumn('subtitle', function ($row) {
                    return substr($row->subtitle, 0, 80) . '...';
                })
                ->addColumn('action', function ($row) {
                    $editimg = asset('/') . 'public/assets/images/edit-round-line.png';
                    $btn = '<a href="' . route('prayer.edit', $row->id) . '" title="Edit"><label class="badge badge-gradient-dark">Edit</label></a> ';
                    $delimg = asset('/') . 'public/assets/images/dlt-icon.png';
                    $btn .= '<a href="" data-bs-toggle="modal" data-bs-target="#staticBackdrop3" class="deldata" id="' . $row->id . '" title="Delete" onclick=\'setData(' . $row->id . ',"' . route('prayer.destroy', $row->id) . '");\'><label class="badge badge-danger">Delete</label></a>';
                    return $btn;
                })
                ->rawColumns(['subtitle', 'action'])
                ->make(true);
        }

        return view('admin.prayer.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        return view('admin.prayer.create', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:200',
            'subtitle' => 'required',
            'description' => 'required',
            'prayer_time' => 'required|in:morning,afternoon,evening',
            'prayer_type' => 'required|in:Barkha,Reshma,Monday',
            'prayer_date' => 'required|date',
            // 'other_info' => 'required',
            // 'ar_title' => 'required',
            // 'ar_subtitle' => 'required',
            // 'ar_description' => 'required',
            // 'ar_other_info' => 'required',
            // 'pe_title' => 'required',
            // 'pe_subtitle' => 'required',
            // 'pe_description' => 'required',
            // 'pe_other_info' => 'required',
        ], [
            'ar_title.required' => 'The title field is required.',
            'ar_subtitle.required' => 'The subtitle field is required.',
            'ar_description.required' => 'The description field is required.',
            // 'ar_other_info.required' => 'The other info field is required.',
            'pe_title.required' => 'The title field is required.',
            'pe_subtitle.required' => 'The subtitle field is required.',
            'pe_description.required' => 'The description field is required.',
            // 'pe_other_info.required' => 'The other info field is required.',
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();
            return back()->withInput()->withErrors($messages);
        } else {
            $password = rand_string(6);
            $prayer = new Prayer();
            $prayer['title'] = $request->title;
            $prayer['subtitle'] = $request->subtitle;
            $prayer['description'] = $request->description;
            $prayer['prayer_time'] = $request->prayer_time;
            $prayer['prayer_type'] = $request->prayer_type;
            $prayer['prayer_date'] = $request->prayer_date;
            // $prayer['other_info'] = $request->other_info;
            $prayer['ar_title'] = $request->ar_title;
            $prayer['ar_subtitle'] = $request->ar_subtitle;
            $prayer['ar_description'] = $request->ar_description;
            // $prayer['ar_other_info'] = $request->ar_other_info;
            $prayer['pe_title'] = $request->pe_title;
            $prayer['pe_subtitle'] = $request->pe_subtitle;
            $prayer['pe_description'] = $request->pe_description;
            // $prayer['pe_other_info'] = $request->pe_other_info;
            if ($request->hasFile('docs')) {
                $destinationPath = 'uploads/';
                $file = $request->file('docs');
                $file_name = time() . '' . $file->getClientOriginalName();
                $file->move($destinationPath, $file_name);
                $imageName = $destinationPath . '' . $file_name;
                $prayer['docs'] = $imageName;
            }
            $prayer->save();
            return redirect('prayer')->with('message', 'Record Added!');
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
        $data['prayer'] = Prayer::find($id);
        return view('admin.prayer.edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:200',
            'subtitle' => 'required',
            'description' => 'required',
            'prayer_time' => 'required|in:morning,afternoon,evening',
            'prayer_type' => 'required|in:Barkha,Reshma,Monday',
            'prayer_date' => 'required|date',
            // 'other_info' => 'required',
            // 'ar_title' => 'required',
            // 'ar_subtitle' => 'required',
            // 'ar_description' => 'required',
            // 'ar_other_info' => 'required',
            // 'pe_title' => 'required',
            // 'pe_subtitle' => 'required',
            // 'pe_description' => 'required',
            // 'pe_other_info' => 'required',
        ], [
            'ar_title.required' => 'The title field is required.',
            'ar_subtitle.required' => 'The subtitle field is required.',
            'ar_description.required' => 'The description field is required.',
            // 'ar_other_info.required' => 'The other info field is required.',
            'pe_title.required' => 'The title field is required.',
            'pe_subtitle.required' => 'The subtitle field is required.',
            'pe_description.required' => 'The description field is required.',
            // 'pe_other_info.required' => 'The other info field is required.',
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();
            return back()->withInput()->withErrors($messages);
        } else {
            $prayer = Prayer::find($id);
            $prayer['title'] = $request->title;
            $prayer['subtitle'] = $request->subtitle;
            $prayer['description'] = $request->description;
            $prayer['prayer_time'] = $request->prayer_time;
            $prayer['prayer_type'] = $request->prayer_type;
            $prayer['prayer_date'] = $request->prayer_date;
            // $prayer['other_info'] = $request->other_info;
            $prayer['ar_title'] = $request->ar_title;
            $prayer['ar_subtitle'] = $request->ar_subtitle;
            $prayer['ar_description'] = $request->ar_description;
            // $prayer['ar_other_info'] = $request->ar_other_info;
            $prayer['pe_title'] = $request->pe_title;
            $prayer['pe_subtitle'] = $request->pe_subtitle;
            $prayer['pe_description'] = $request->pe_description;
            // $prayer['pe_other_info'] = $request->pe_other_info;
            if ($request->hasFile('docs')) {
                $destinationPath = 'uploads/';
                $file = $request->file('docs');
                $file_name = time() . '' . $file->getClientOriginalName();
                $file->move($destinationPath, $file_name);
                $imageName = $destinationPath . '' . $file_name;
                $prayer['docs'] = $imageName;
            }
            $prayer->save();
            return redirect('prayer')->with('message', 'Record Updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Prayer::where('id', $id)->delete();
    }
}
