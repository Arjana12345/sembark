<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {

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
