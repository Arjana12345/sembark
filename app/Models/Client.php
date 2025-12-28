<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public $table = "client";
     protected $fillable = [
                            'id',
                            'client_name',
                            'user_id',
                            'client_email'
                        ]; 
}
