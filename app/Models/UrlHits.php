<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UrlHits extends Model
{
    public $table = "url_hits";
    protected $fillable = [
                            'id',
                            'hit_count',
                            'short_url_id',
                            'user_id',
                            'created_at',
                            'updated_at'
                        ]; 

    public $timestamps = true;
}
