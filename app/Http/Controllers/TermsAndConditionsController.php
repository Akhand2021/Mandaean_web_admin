<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTermsRequest;
use App\Models\TermsAndCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Resources\TermsAndConditionCollection;

class TermsAndConditionsController extends Controller
{
    // List view with DataTable support
    /**
     * Display a listing of the resource.
     * @OA\Get(
     *     path="/api/terms-and-conditions",
     *     tags={"Terms and Conditions"},
     *     summary="Get all terms and conditions",
     *     security={{"apiKey":{}},{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of terms and conditions."
     *     )
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $terms = TermsAndCondition::select(['id', 'title', 'content', 'is_active', 'updated_at']);
        if ($request->ajax()) {

            return DataTables::of($terms)
                ->addColumn('action', function ($term) {
                    $editUrl = route('terms-and-conditions.edit', $term->id);
                    $deleteUrl = route('terms-and-conditions.destroy', $term->id);
                    return view('admin.terms.action-buttons', compact('editUrl', 'deleteUrl'))->render();
                })
                ->editColumn('is_active', function ($term) {
                    return $term->is_active;
                })
                ->editColumn('updated_at', function ($term) {
                    return $term->updated_at->format('Y-m-d H:i:s');
                })
                ->rawColumns(['action'])
                ->make(true);
        } else {
            return response()->json(new TermsAndConditionCollection($terms->get()));
        }

        return view('admin.terms.index', ['data' => ['sort_name' => '']]);
    }

    // Show create form
    public function create()
    {
        return view('admin.terms.create', ['data' => ['sort_name' => '']]);
    }

    // Store new T&C entry
    public function store(StoreTermsRequest $request)
    {


        TermsAndCondition::create([
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('terms-and-conditions.index')->with('success', 'Terms & Conditions created successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $term = TermsAndCondition::findOrFail($id);
        return view('admin.terms.edit', ['data' => ['sort_name' => '', 'term' => $term]]);
    }

    // Show details of a specific entry
    public function show($id)
    {
        $term = TermsAndCondition::findOrFail($id);
        return view('admin.terms.show', ['data' => ['sort_name' => '', 'term' => $term]]);
    }

    // Update existing entry
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required|string|min:10',
            'is_active' => 'boolean',
        ]);

        $term = TermsAndCondition::findOrFail($id);
        $term->update([
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('terms-and-conditions.index')->with('success', 'Terms & Conditions updated successfully.');
    }

    // Delete entry
    public function destroy($id)
    {
        $term = TermsAndCondition::findOrFail($id);
        $term->delete();

        return redirect()->route('terms-and-conditions.index')->with('success', 'Terms & Conditions deleted successfully.');
    }
}
