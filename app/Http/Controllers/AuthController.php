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
                $request->session()->put('clientId', $user->client_id);
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
        ########################
        ## USer details
        ########################
        if(Session::has('loginId'))
        {
            $data = User::where('id','=',Session::get('loginId'))->first();

        }

        ########################
        ## Client list for Super Admin
        ########################
        $client_list = array();
        $client_total_urls = array();
        $client_total_hits = array();
        if(Session::get('rolId') == 1)
        {
            ##################################################
            ## SQL: client list with tutal users in a client company
            ##################################################
            $sql1 = 'SELECT client.id, client.client_name, client.client_email, count(users.id) as total_users from client join users on users.client_id = client.id group by client.id';
            $sql1_result = DB::select($sql1);
            $client_list = DB::table(DB::raw("($sql1) as sub"))
                            ->simplePaginate(2);

            ##################################################
            ## SQL: client list with total urls in that client company 
            ##################################################
            $sql2 = 'SELECT count(short_url.id)as total_url, client.id as client_id from short_url join users on users.id = short_url.user_id join client on client.id = users.client_id GROUP by short_url.user_id';
            $total_urls = DB::select($sql2);

            foreach($total_urls  as $this_url_count)
            {
               $client_total_urls[$this_url_count->client_id] = $this_url_count->total_url;
            }

            ##################################################
            ## SQL: client list with total hits in that company
            ##################################################
            $sql3 = 'SELECT SUM(COALESCE(url_hits.hit_count, 0)) as total_hits, client.id as client_id FROM url_hits join users on users.id = url_hits.user_id join client on client.id = users.client_id GROUP by client.id';
            $total_hits = DB::select($sql3);
            
            foreach($total_hits  as $this_hit_count)
            {
               $client_total_hits[$this_hit_count->client_id] = $this_hit_count->total_hits;
            }

        }
    
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
                              ->simplePaginate(2);
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
                               ->simplePaginate(2);

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
