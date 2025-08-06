<?php

namespace App\Actions\TransactionActions;

use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class PurgeUserTransactions
{
    public function handle($userId)
    {
        DB::transaction(function () use ($userId) {
            Transaction::where('user_id', $userId)->delete();
            self::cacheFlush();
        });
    }

    public static function cacheFlush()
    {
        Cache::flush();
    }
}
