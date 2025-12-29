<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\ShortUrl;

class ShortUrlController extends Controller
{
    public function index()
    {
        $short_url_list = ShortUrl::where('user_id','=',Session::get('loginId'))->simplePaginate(10);
        return view('short_url.index', compact('short_url_list'));
       
    }

    public function create()
    {
        return view('short_url.create');
    }
    
    public function store(Request $request)
    {
        $data = $request->validate([
                'short_url' => 'required',       # form fields
                'long_url' => 'required' 
            ]            
        );

        $data['user_id'] = Session::get('loginId');
        
        $new_url = ShortUrl::create($data);
        
        return redirect('short_url/')->with('success', 'Short URL Added');
    }


}
