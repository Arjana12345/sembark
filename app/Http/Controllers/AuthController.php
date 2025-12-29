<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\ShortUrl;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
    public function dashboard()
    {
        $data = array();
        if(Session::has('loginId'))
        {
            $data = User::where('id','=',Session::get('loginId'))->first();

        }

        $client_list = array();
        if(Session::get('rolId') == 1)
        {
            // Get client data
            $client_list = Client::where('user_id','=',Session::get('loginId'))->simplePaginate(2);
        }
      
        $short_url_list = array();
        if(Session::get('rolId') == 1)
        {
            // Get short_url_list data
            $short_url_list = ShortUrl::where('user_id','=',Session::get('loginId'))->simplePaginate(2);
        }
        return view('user.dashboard',compact('data', 'client_list', 'short_url_list'));
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
