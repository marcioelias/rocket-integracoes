<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    protected $fillable = [
        'name', 'base_url', 'auth_method', 'token', 'username', 'password'
    ];

    public function endpoints() {
        return $this->hasMany(ApiEndpoint::class);
    }

    public function scopeOrdered($query) {
        return $query->orderBy('name', 'asc');
    }
}
