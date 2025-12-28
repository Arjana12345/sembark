<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;



Route::get('/', function () {
    return view('welcome'); 
});

#########################
## User profile
##########################
Route::controller(AuthController::class)->group(function(){
    Route::get('/login','login');
    Route::post('/login-user','loginUser')->name('login-user');
    Route::get('/dashboard','dashboard')->middleware('session_check');
    Route::get('/logout','logout');
});