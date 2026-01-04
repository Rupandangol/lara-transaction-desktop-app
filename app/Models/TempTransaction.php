<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempTransaction extends Model
{
    protected $fillable = [
        'date_time',
        'description',
        'debit',
        'credit',
        'status',
        'channel',
        'tag',
        'user_id',
    ];
}
