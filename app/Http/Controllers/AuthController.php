<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\ShortUrl;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{
    ################
    ## Login Display
    ###############
    public function login()
    {
       return view('auth.login');
    }
    
    ################
    ## Login Submit
    ###############
    public function loginUser(Request $request)
    {
        echo 'Login submited'; 
       
        
        $request->validate([            
            'email'=>'required|email:users',
            'password'=>'required|min:8'
        ]);
      
        $user = User::where('email','=',$request->email)->first();
        print_r($user);
       
        if($user)
        {

            if(HASH::check($request->password, $user->password))
            {
                echo 'password match';
                $request->session()->put('loginId', $user->id);
                $request->session()->put('userName', $user->name);
                $request->session()->put('rolId', $user->rol_id);
                return redirect('dashboard');
            }
            else 
            {
                echo 'password not match';
                return back()->with('fail','Password not match!');
            }
        } 
        else
        {
            echo 'email not found';
            return back()->with('fail','This email is not register.');
        } 
              
        
    }
   
    ################
    ## Dashboard
    ###############
    public function dashboard(Request $request)
    {
        $data = array();
        if(Session::has('loginId'))
        {
            $data = User::where('id','=',Session::get('loginId'))->first();

        }

        $client_list = array();
        $client_total_urls = array();
        $client_total_hits = array();
        if(Session::get('rolId') == 1)
        {
            // Get client data
            #$client_list = Client::where('user_id','=',Session::get('loginId'))->simplePaginate(2);
            $sql1 = 'SELECT client.id, client.client_name, client.client_email, count(users.id) as total_users from client join users on users.client_id = client.id group by client.id';
            $sql1_result = DB::select($sql1);
            $client_list = DB::table(DB::raw("($sql1) as sub"))
                            ->simplePaginate(2);

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
    
        $short_url_list = array();
        if(Session::get('rolId') == 1)
        {
            // Get short_url_list data
            #$short_url_list = ShortUrl::where('user_id','=',Session::get('loginId'))->simplePaginate(2);
            
            $sql = 'SELECT short_url.id,short_url.short_url,short_url.long_url,short_url.created_at,short_url.user_id,users.name,SUM(COALESCE(url_hits.hit_count, 0)) as total_hits FROM short_url left join users on users.id=short_url.user_id left join url_hits on url_hits.short_url_id = short_url.id group by short_url.id';
            $short_url_list = DB::table(DB::raw("($sql) as sub"))
                    ->simplePaginate(2);


        }
        return view('user.dashboard',compact('data', 'client_list', 'client_total_urls', 'client_total_hits', 'short_url_list'));
    }

    ################
    ## Logout
    ###############
    public function logout()
    {
        $data = array();
        if(Session::has('loginId'))
        {
            Session::pull('loginId');
            return redirect('login');
        }
    }
}
