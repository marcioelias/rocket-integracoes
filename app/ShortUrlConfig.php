<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShortUrlConfig extends Model
{
    protected $fillable = [
        'short_domain', 'short_url_api', 'api_key'
    ];
}
