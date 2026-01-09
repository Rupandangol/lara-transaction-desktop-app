<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAiKey extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'provider',
        'api_key',
        'is_active'
    ];

    protected $casts = [
        'api_key' => 'encrypted',
        'is_active' => 'boolean'
    ];
}
