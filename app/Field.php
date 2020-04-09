<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = [
        'label', 'field_name', 'system_field', 'can_delete'
    ];

    public function scopeNonSystem($query) {
        return $query->where('system_field', false);
    }
}
