<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index()
    {
       
        $client_list = array();
        $client_total_urls = array();
        $client_total_hits = array();
        if(Session::get('rolId') == 1)
        {
            // Get client data
            $sql1 = 'SELECT client.id, client.client_name, client.client_email, count(users.id) as total_users from client left join users on client.id = users.client_id group by client.id';
            $sql1_result = DB::select($sql1);
            $client_list = DB::table(DB::raw("($sql1) as sub"))
                            ->simplePaginate(10);

            $sql2 = 'SELECT count(short_url.id)as total_url, client.id as client_id from short_url join users on users.id = short_url.user_id join client on client.id = users.client_id GROUP by short_url.user_id';
            $total_urls = DB::select($sql2);

            foreach($total_urls  as $this_url_count)
            {
               $client_total_urls[$this_url_count->client_id] = $this_url_count->total_url;
            }


            $sql3 = 'SELECT SUM(COALESCE(url_hits.hit_count, 0)) as total_hits, client.id as client_id FROM url_hits join users on users.id = url_hits.user_id join client on client.id = users.client_id GROUP by client.id';
            $total_hits = DB::select($sql3);
            
            foreach($total_hits  as $this_hit_count)
            {
               $client_total_hits[$this_hit_count->client_id] = $this_hit_count->total_hits;
            }

        }
        else 
        {
            return redirect('/dashboard');
        }
        return view('client.index', compact('client_list','client_total_urls','client_total_hits'));

       
    }

    public function create()
    {
        return view('client.create');
    }
    
    public function store(Request $request)
    {
        $data = $request->validate([
            
            'client_name' => 'required',       # form fields
            'client_email' => 'required' 

            ]            
        );

        $data['user_id'] = Session::get('loginId');
        
        $new_client = Client::create($data);
        
        return redirect('client/invite')->with('success', 'Client Invited!');
    }
    
}
