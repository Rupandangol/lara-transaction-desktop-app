<?php

use App\Http\Controllers\SettingController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\UserController;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    Route::get('/transactions', [TransactionsController::class, 'index'])->name('transaction.index');
    Route::post('/transactions/import', [TransactionsController::class, 'import'])->name('transaction.import');
    Route::get('/transactions/sample', [TransactionsController::class, 'sample'])->name('transaction.sample');
    Route::get('/transactions/purge', [TransactionsController::class, 'purge'])->name('transaction.purge');
    Route::patch('/user',[UserController::class,'update'])->name('user.update');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
