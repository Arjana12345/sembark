<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    public $table = "short_url";
    protected $fillable = [
                            'id',
                            'short_url',
                            'long_url',
                            'user_id',
                            'created_at',
                            'updated_at',
                        ]; 

    public $timestamps = true;
}
