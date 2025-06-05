<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StaticPage;

class WebviewController extends Controller
{
    public function app_term(Request $request)
    {
        $page=$request->page;
        $result = StaticPage::where('slug',$page)->first();
        
        $data['row']['content']=$result->content;
        
        return view('front.app_static_page',$data);
    }
}
