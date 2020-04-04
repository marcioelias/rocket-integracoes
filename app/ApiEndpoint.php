<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiEndpoint extends Model
{
    protected $fillable = [
        'name', 'api_id', 'relative_url', 'method', 'body', 'json', 'field_ok', 'code_ok'
    ];

    public function api() {
        return $this->belongsTo(Api::class);
    }

    public function actions() {
        return $this->hasMany(Action::class);
    }

    public function scopeOrdered($query) {
        return $query->orderBy('name', 'asc');
    }

    public function scopeByApi($query, Api $api) {
        return $query->where('api_id', $api->id);
    }
}
