<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ShortUrlController;



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


#########################
## client
##########################
#########################
## Route group
##########################
Route::middleware(['session_check'])->group(function(){

        Route::group(['prefix' => '/client'],function(){
        Route::get('/', [ClientController::class,'index'])->name('client.index');
        Route::get('/invite', [ClientController::class,'create'])->name('client.create');
        Route::post('/', [ClientController::class,'store'])->name('client.store');
       
    });

});


#########################
## client
##########################
#########################
## Route group
##########################
Route::middleware(['session_check'])->group(function(){

        Route::group(['prefix' => '/short_url'],function(){
        Route::get('/', [ShortUrlController::class,'index'])->name('surl.index');
        Route::get('/create', [ShortUrlController::class,'create'])->name('surl.create');
        Route::post('/', [ShortUrlController::class,'store'])->name('surl.store');
       
    });

});