<?php

use App\Http\Controllers\TransactionsController;
use App\Models\Transaction;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // User::factory()->createOne();
    // Transaction::factory()->createOne();
    $user = Transaction::all();
    // $transactions = Transaction::all();
    return view('welcome');
});

Route::get('/transactions', [TransactionsController::class, 'index'])->name('transaction.index');
Route::post('/transactions/import', [TransactionsController::class, 'import'])->name('transaction.import');
