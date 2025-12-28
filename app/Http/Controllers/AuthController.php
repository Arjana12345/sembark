<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    //For Login

    
    public function login()
    {
        //echo 'Arjana';
        
        return view('auth.login');
    }
    public function loginUser(Request $request)
    {
        echo 'Login submited';       
    }
   
}
