<?php

namespace Tests\Unit;

use App\Actions\TransactionActions\PurgeUserTransactions;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurgeUserTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_purge_user_transaction_action(): void
    {
        $user1 = User::factory()->createOne();
        $user2 = User::factory()->createOne();
        Transaction::factory()->createOne(['user_id' => $user1]);
        Transaction::factory()->createOne(['user_id' => $user2]);
        $action = new PurgeUserTransactions;
        $action->handle($user1->id);
        $this->assertDatabaseCount('transactions', 1);
    }
}
