<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
     public function index()
    {
       
        $data = array();
        ########################
        ## USer details
        ########################
        if(Session::has('loginId'))
        {
            $data = User::where('id','=',Session::get('loginId'))->first();

        }
        #########################
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
                                ->simplePaginate(10);

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
                                    ->simplePaginate(10);

            foreach($url_hits  as $this_hit_count)
            {
               $team_member_url_hits[$this_hit_count->user_id] = $this_hit_count->total_hits;
            }

            
        }
        else 
        {
            return redirect('/dashboard');
        }
        return view('user.index', compact('data', 'team_member_list', 'team_member_total_urls', 'team_member_url_hits'));

       
    }

    public function create()
    {
        
        return view('user.create');
    }
    
    public function store(Request $request)
    {
        $client_id = Session::get('clientId');

        $data = $request->validate([
            
            'name' => 'required',       # form fields
            'email' => 'required',
            'rol_id' => 'required'
            ]            
        );

        $data['password'] = '12345678'; # default password
        $data['client_id'] = $client_id;
        $data['password'] = '12345678'; # default password

        $new_user = User::create($data);
        
        return redirect('user/invite')->with('success', 'User Invited!');
    }

}
