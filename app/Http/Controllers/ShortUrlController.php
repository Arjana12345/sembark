<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\ShortUrl;
use Illuminate\Support\Facades\DB;

class ShortUrlController extends Controller
{
    public function index()
    {
        ########################
        ## Short urls
        ########################
        $short_url_list = array();
        if(Session::get('rolId') == 1)
        {
            ####################
            ## Super Admin
            ####################
            $sql = 'SELECT short_url.id,short_url.short_url,short_url.long_url,short_url.created_at,short_url.user_id,users.name,SUM(COALESCE(url_hits.hit_count, 0)) as total_hits FROM short_url left join users on users.id=short_url.user_id left join url_hits on url_hits.short_url_id = short_url.id group by short_url.id';
            $short_url_list = DB::table(DB::raw("($sql) as sub"))
                              ->simplePaginate(10);
        }
        else if(Session::get('rolId') == 2)
        {
            ####################
            ## Admin
            ####################
            $client_id = Session::get('clientId');

            $sql = "SELECT short_url.id,short_url.short_url,short_url.long_url,short_url.created_at,short_url.user_id,users.name,SUM(COALESCE(url_hits.hit_count, 0)) as total_hits FROM short_url left join users on users.id=short_url.user_id left join url_hits on url_hits.short_url_id = short_url.id where users.client_id= ? group by short_url.id";
            $short_url_list = DB::table(DB::raw("($sql) as sub"))
                                ->setBindings([$client_id])
                               ->simplePaginate(10);

        }
        else 
        {
            ####################
            ## Member
            ####################
            $user_id = Session::get('loginId');

            $sql = "SELECT short_url.id,short_url.short_url,short_url.long_url,short_url.created_at,short_url.user_id,users.name,SUM(COALESCE(url_hits.hit_count, 0)) as total_hits FROM short_url left join users on users.id=short_url.user_id left join url_hits on url_hits.short_url_id = short_url.id where users.id=? group by short_url.id";
            
            $short_url_list = DB::table(DB::raw("($sql) as sub"))
                                ->setBindings([$user_id])
                               ->simplePaginate(10);
        }
        
        
        return view('short_url.index', compact('short_url_list'));
       
    }

    public function create()
    {
        if(Session::get('rolId') == 1)
        {
            return redirect('dashboard');
            
        }
        else
        {
           return view('short_url.create');
        }
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
