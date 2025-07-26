<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Dflydev\DotAccessData\Data;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Yajra\DataTables\Facades\DataTables;

class FaqController extends Controller
{
    /**
     * Display a listing of FAQs (GET API)
     */
    public function index(Request $request)
    {
        $data['filter'] = $request->filter;
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        try {
            $faqs = Faq::orderBy('created_at', 'desc')->get();
            // For web view
            if ($request->ajax()) {
                return DataTables::of($faqs)
                    ->addColumn('action', function ($faq) {
                        $editimg = asset('/') . 'public/assets/images/edit-round-line.png';
                        $btn = '<a href="' . route('faqs.edit', $faq->id) . '" title="Edit"><label class="badge badge-gradient-dark">Edit</label></a> ';
                        $delimg = asset('/') . 'public/assets/images/dlt-icon.png';
                        $btn .= '<a href="" data-bs-toggle="modal" data-bs-target="#staticBackdrop3" class="deldata" id="' . $faq->id . '" title="Delete" onclick=\'setData(' . $faq->id . ',"' . route('faqs.destroy', $faq->id) . '");\'><label class="badge badge-danger">Delete</label></a>';
                        return $btn;
                    })
                    ->editColumn('is_active', function ($faq) {
                        return $faq->is_active ? 'Yes' : 'No';
                    })
                    ->editColumn('created_at', function ($faq) {
                        return $faq->created_at->format('Y-m-d H:i:s');
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('admin.faqs.index', ['data' => $data]);
        } catch (\Exception $e) {
            return view('admin.faqs.index', [
                'data' => $data,
                'error' => 'An unexpected error occurred: ' . $e->getMessage()
            ]);
        }
    }
    /**
     * Show the form for creating a new FAQ
     */
    public function create(): View
    {
        return view('admin.faqs.create', ['data' => ['sort_name' => '']]);
    }
    /**
     * Store a newly created FAQ
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'question' => 'required|string|max:500',
                'answer' => 'required|string',
                'is_active' => 'boolean',
            ]);

            $faq = Faq::create($validated);

            return redirect()->route('faqs.index')->with('success', 'FAQ created successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->route('faqs.index')->with('error', 'Error creating FAQ: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified FAQ
     */
    public function show($id): View
    {
        try {
            $faq = Faq::findOrFail($id);

            return view('admin.faqs.show', ['faq' => $faq]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return view('admin.faqs.show', ['error' => 'FAQ not found']);
        } catch (\Exception $e) {
            return view('admin.faqs.show', ['error' => 'Error retrieving FAQ: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the specified FAQ
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $faq = Faq::findOrFail($id);

            $validated = $request->validate([
                'question' => 'sometimes|required|string|max:500',
                'answer' => 'sometimes|required|string',
                'is_active' => 'sometimes|boolean',
            ]);

            $faq->update($validated);

            return redirect()->route('faqs.index')->with('success', 'FAQ updated successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('faqs.index')->with('error', 'FAQ not found.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating FAQ: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified FAQ
     */
    public function edit($id): View|RedirectResponse
    {
        try {
            $adminuser = session()->get('adminuser');
            $data['sort_name'] = $adminuser->name;
            $data['faq'] = Faq::findOrFail($id);
            return view('admin.faqs.edit', ['data' => $data]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('faqs.index')->with('error', 'FAQ not found.');
        } catch (\Exception $e) {
            return redirect()->route('faqs.index')->with('error', 'Error retrieving FAQ: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified FAQ
     */
    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        return $faq->delete();
    }
}
