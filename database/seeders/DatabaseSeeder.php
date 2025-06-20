<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Transaction::factory()->createOne();

        User::factory()->create([
            'name' => 'asdf User',
            'email' => 'tesasdft@example.com',
        ]);
    }
}
