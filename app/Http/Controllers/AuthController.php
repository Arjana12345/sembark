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
        
        if($user)
        {

            if(HASH::check($request->password, $user->password))
            {
                $request->session()->put('loginId', $user->id);
                $request->session()->put('userName', $user->name);
                $request->session()->put('rolId', $user->rol_id);
                $request->session()->put('clientId', $user->client_id);
                return redirect('dashboard');
            }
            else 
            {
               return back()->with('fail','Password not match!');
            }
        } 
        else
        {
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
            $sql1 = 'SELECT client.id, client.client_name, client.client_email, count(users.id) as total_users from client left join users on client.id = users.client_id group by client.id';
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
        
        ########################
        ## Team member for admin
        ########################
        $team_member_list = array();
        $team_member_url_hits = array();
        $team_member_total_urls = array();
        if(Session::get('rolId') == 2)
        {
            $user_id = Session::get('loginId');

            
            ###########################
            ## All team member basic details
            ############################
            
            $team_member_list = DB::table('users')
                                ->where('client_id', '=', Session::get('clientId'))
                                ->whereNotIn('id', [$user_id]) ## exclude to itself and super admin
                                ->whereNotIn('rol_id', [1]) ## exclude to itself and super admin
                                ->simplePaginate(2);
            
            #########################################################
            ## All team member basi details and total  short urls
            #########################################################
            $user_id   = Session::get('loginId');
            $client_id = Session::get('clientId');

            $sql = "SELECT count(short_url.id) as total_urls, short_url.user_id, users.client_id from short_url join users on users.id = short_url.user_id where users.client_id = ? and users.id != ? and users.rol_id != '1' group by short_url.user_id ";
            $short_urls = DB::table(DB::raw("($sql) as sub"))
                                ->setBindings([$client_id, $user_id]) ## exclude to itself and super admin
                                ->simplePaginate(2);

            foreach($short_urls  as $this_short_url)
            {
               $team_member_total_urls[$this_short_url->user_id] = $this_short_url->total_urls;
            }
            #########################################################
            ## All team member total url hits
            #########################################################
            $sql = "select SUM(COALESCE(url_hits.hit_count, 0)) as total_hits,url_hits.user_id from url_hits JOIN short_url on short_url.id = url_hits.short_url_id join users on users.id = url_hits.user_id where users.client_id = ? and users.id != ? and users.rol_id != '1' GROUP by url_hits.user_id";
            $url_hits = DB::table(DB::raw("($sql) as sub"))
                                    ->setBindings([$client_id, $user_id]) ## exclude to itself and super admin
                                    ->simplePaginate(2);

            foreach($url_hits  as $this_hit_count)
            {
               $team_member_url_hits[$this_hit_count->user_id] = $this_hit_count->total_hits;
            }

            
        }
        
        return view('user.dashboard',compact('data', 'client_list', 'client_total_urls', 'client_total_hits', 'short_url_list', 'team_member_list', 'team_member_total_urls', 'team_member_url_hits'));
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
