<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Enum\ChannelEnum;
use App\Enum\StatusEnum;
use App\Enum\TagEnum;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_shows_transactions_for_current_year_by_default()
    {
        $user = User::factory()->createOne();
        $this->actingAs($user);
        $response = $this->get(route('transaction.index'));

        $response->assertStatus(200);
        $response->assertViewHas('transactions');
    }

    public function test_authenticated_user_can_create_transaction()
    {
        $response1 = $this->post(route('transaction.store', [
            'date_time' => Carbon::now()->format('Y-m-d H:i:s'),
            'description' => 'test',
            'transaction_type' => 'debit',
            'amount' => '123',
            'status' => StatusEnum::COMPLETE,
            'channel' => ChannelEnum::CASH,
            'tag' => TagEnum::BILL_SHARING,
        ]));
        $this->assertDatabaseCount('transactions', 0);
        $user = User::factory()->createOne();
        $this->actingAs($user);
        $response2 = $this->post(route('transaction.store', [
            'date_time' => Carbon::now()->format('Y-m-d H:i:s'),
            'description' => 'test',
            'transaction_type' => 'debit',
            'amount' => '123',
            'status' => StatusEnum::COMPLETE,
            'channel' => ChannelEnum::CASH,
            'tag' => TagEnum::BILL_SHARING,
        ]));

        $this->assertDatabaseCount('transactions', 1);
    }

    public function test_authenticated_user_can_update_transaction()
    {
        $user = User::factory()->createOne();
        $this->actingAs($user);
        $transaction = Transaction::factory()->createOne(['user_id' => $user->id]);
        $response2 = $this->patch(route('transaction.update', $transaction->id), [
            'date_time' => '2020-01-01',
            'description' => 'test',
            'transaction_type' => 'debit',
            'amount' => '123',
            'status' => 'COMPLETE',
            'channel' => 'CASH',
            'tag' => 'bill_sharing',
        ]);
        $this->assertDatabaseCount('transactions', 1);
        $this->assertDatabaseHas('transactions', [
            'date_time' => '2020-01-01 00:00:00',
            'description' => 'test',
            'debit' => '123',
            'status' => 'COMPLETE',
            'channel' => 'CASH',
            'tag' => 'bill_sharing',
        ]);
    }

    public function test_it_exports_transactions_as_csv()
    {
        $user = User::factory()->createOne();
        $this->actingAs($user);

        Transaction::factory()->createOne(['user_id' => $user->id]);

        $response = $this->get(route('transaction.export'));

        $response->assertStatus(200);
    }

    public function test_it_imports_a_valid_csv_file_successfully()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Storage::fake('local');

        $header = 'Id,Date and time,Description,Debit(Rs.),Credit(Rs.),Status,Balance(Optional),Channel,Tag';
        $row1 = '1 ,2020-01-01,test,123,0,COMPLETE,,CASH,bill_sharing';
        $row2 = '2 ,2020-01-01,test2,123,0,COMPLETE,,CASH,bill_sharing';

        $content = implode("\n", [$header, $row1, $row2]);
        $file = UploadedFile::fake()->createWithContent('transactions.csv', $content);

        $response = $this->post(route('transaction.import'), [
            'transaction_file' => $file,
            'source_type' => 'default',
        ]);
        $this->assertDatabaseCount('transactions', 2);
    }

    public function test_it_fails_validation_with_invalid_file_type()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->create('invalid.pdf', 100, 'application/pdf');

        $response = $this->post(route('transaction.import'), [
            'transaction_file' => $file,
            'source_type' => 'default',
        ]);

        $response->assertSessionHasErrors('transaction_file');
    }

    public function test_it_fails_validation_with_invalid_source_type()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->create('transactions.csv', 100, 'text/csv');

        $response = $this->post(route('transaction.import'), [
            'transaction_file' => $file,
            'source_type' => 'invalid_source',
        ]);

        $response->assertSessionHasErrors('source_type');
    }
}
