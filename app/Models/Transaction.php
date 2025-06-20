<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionsFactory> */
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'date_time',
        'description',
        'debit',
        'credit',
        'status',
        'channel',
        'user_id',
    ];
}
