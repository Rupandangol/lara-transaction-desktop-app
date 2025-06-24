<?php

use App\Http\Controllers\TransactionsController;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    Route::get('/transactions', [TransactionsController::class, 'index'])->name('transaction.index');
    Route::post('/transactions/import', [TransactionsController::class, 'import'])->name('transaction.import');
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
