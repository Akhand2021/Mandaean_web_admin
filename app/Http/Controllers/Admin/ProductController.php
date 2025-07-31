<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Size;
use Validator;
use Hash;
use App\Helper\Helper;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['filter'] = $request->filter;
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        $dataList = Product::with('images')->whereNot('status', 'deleted')->orderBy('id', 'desc');
        $search = $request->search;
        if ($search) {
            $dataList->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('price', 'LIKE', '%' . $search . '%');
        }
        $dataList = $dataList->get();

        if ($request->ajax()) {
            return DataTables::of($dataList)
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="product-checkbox" value="' . $row->id . '">';
                })
                ->addColumn('condition', function ($row) {
                    return $row->condition ? $row->condition : 'N/A';
                })
                ->editColumn('image', function ($row) {
                    if (count($row->images) > 0) {
                        return '<img src="' . url('/') . '/' . $row->images[0]->image . '">';
                    } else {
                        return 'N/A';
                    }
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 'active' ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $editimg = asset('/') . 'public/assets/images/edit-round-line.png';
                    $btn = '<a href="' . route('product.edit', $row->id) . '" title="Edit"><label class="badge badge-gradient-dark">Edit</label></a> ';
                    $delimg = asset('/') . 'public/assets/images/dlt-icon.png';
                    $btn .= '<a href="" data-bs-toggle="modal" data-bs-target="#staticBackdrop3" class="deldata" id="' . $row->id . '" title="Delete" onclick=\'setData(' . $row->id . ',"' . route('product.destroy', $row->id) . '");\'><label class="badge badge-danger">Delete</label></a>';
                    return $btn;
                })
                ->rawColumns(['checkbox', 'status', 'image', 'action'])
                ->make(true);
        }

        return view('admin.product.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $adminuser = session()->get('adminuser');
        $data['sort_name'] = $adminuser->name;
        $data['sizes'] = Size::where('status', 'active')->get();
        return view('admin.product.create', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:200',
            'inventory' => 'required',
            'price' => 'required|numeric',
            'size' => 'required',
            'sizeprice' => 'required',
            // 'condition' => 'required',
            // 'ar_name' => 'required|max:200',
            // 'ar_category' => 'required',
            // 'ar_condition' => 'required',
            // 'pe_name' => 'required|max:200',
            // 'pe_category' => 'required',
            // 'pe_condition' => 'required',
            'photo' => 'required',
            'status' => 'required',
        ], [
            'ar_name.required' => 'The name field is required.',
            'ar_category.required' => 'The category field is required.',
            'ar_condition.required' => 'The condition field is required.',
            'pe_name.required' => 'The name field is required.',
            'pe_category.required' => 'The category field is required.',
            'pe_condition.required' => 'The condition field is required.',
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();
            return back()->withInput()->withErrors($messages);
        } else {
            $sizeprice = $request->sizeprice;
            foreach ($sizeprice as $key => $value) {
                if (is_null($value) || $value == '')
                    unset($sizeprice[$key]);
            }
            $sizeprice = array_map('floatval', array_values($sizeprice));

            $product = new Product();
            $product['name'] = $request->name;
            $product['sku'] = _getSKU();
            $product['status'] = $request->status;
            $product['price'] = $request->price;
            $product['inventory'] = $request->inventory;
            $product['size_ids'] = array_map('intval', $request->size);
            $product['sizeprice'] = $sizeprice;
            $product['condition'] = $request->condition;
            // $product['ar_name'] = $request->ar_name;
            // $product['ar_category'] = $request->ar_category;
            // $product['ar_condition'] = $request->ar_condition;
            // $product['pe_name'] = $request->pe_name;
            // $product['pe_category'] = $request->pe_category;
            // $product['pe_condition'] = $request->pe_condition;
            $product->save();

            if ($request->hasfile('photo')) {
                foreach ($request->file('photo') as $key => $file) {
                    $imageName = upload_file_common($file);
                    ProductImage::create(['product_id' => $product->id, 'image' => $imageName]);
                }
            }
            return redirect('product')->with('message', 'Record Added!');
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
        $data['sizes'] = Size::where('status', 'active')->get();
        $data['product'] = Product::with('images')->find($id);
        return view('admin.product.edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:200',
            'inventory' => 'required',
            'price' => 'required|numeric',
            'size' => 'required',
            'sizeprice' => 'required',
            // 'condition' => 'required',
            // 'ar_name' => 'required|max:200',
            // 'ar_category' => 'required',
            // 'ar_condition' => 'required',
            // 'pe_name' => 'required|max:200',
            // 'pe_category' => 'required',
            // 'pe_condition' => 'required',
            // 'photo' => 'required',
            'status' => 'required',
        ], [
            'ar_name.required' => 'The name field is required.',
            'ar_category.required' => 'The category field is required.',
            'ar_condition.required' => 'The condition field is required.',
            'pe_name.required' => 'The name field is required.',
            'pe_category.required' => 'The category field is required.',
            'pe_condition.required' => 'The condition field is required.',
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages();
            return back()->withInput()->withErrors($messages);
        } else {
            $sizeprice = $request->sizeprice;
            foreach ($sizeprice as $key => $value) {
                if (is_null($value) || $value == '')
                    unset($sizeprice[$key]);
            }
            $sizeprice = array_map('floatval', array_values($sizeprice));

            $product = Product::find($id);
            $product['name'] = $request->name;
            $product['price'] = $request->price;
            $product['inventory'] = $request->inventory;
            $product['status'] = $request->status;
            $product['size_ids'] = array_map('intval', $request->size);
            $product['sizeprice'] = $sizeprice;
            $product['condition'] = $request->condition;
            // $product['ar_name'] = $request->ar_name;
            // $product['ar_category'] = $request->ar_category;
            // $product['ar_condition'] = $request->ar_condition;
            // $product['pe_name'] = $request->pe_name;
            // $product['pe_category'] = $request->pe_category;
            // $product['pe_condition'] = $request->pe_condition;
            $product->save();

            if ($request->hasfile('photo')) {
                foreach ($request->file('photo') as $key => $file) {
                    $imageName = upload_file_common($file);
                    ProductImage::create(['product_id' => $product->id, 'image' => $imageName]);
                }
            }
            return redirect('product')->with('message', 'Record Updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->status = 'deleted';
        $product->save();

        return true;
    }

    public function massdestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:products,id',
        ]);

        Product::whereIn('id', $request->ids)->update(['status' => 'deleted']);

        return response()->json(['success' => 'Products marked as deleted successfully.']);
    }

    public function RemoveImage(Request $request)
    {
        ProductImage::where('id', $request->id)->delete();

        return true;
    }
}
