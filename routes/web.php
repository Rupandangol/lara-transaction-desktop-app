<?php

use App\Http\Controllers\SettingController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => '/transactions'], function () {
        Route::get('/', [TransactionsController::class, 'index'])->name('transaction.index');
        Route::post('/import', [TransactionsController::class, 'import'])->name('transaction.import');
        Route::get('/sample', [TransactionsController::class, 'sample'])->name('transaction.sample');
        Route::get('/purge', [TransactionsController::class, 'purge'])->name('transaction.purge');
        Route::get('/create', [TransactionsController::class, 'create'])->name('transaction.create');
        Route::get('/export', [TransactionsController::class, 'export'])->name('transaction.export');
        Route::post('/store', [TransactionsController::class, 'store'])->name('transaction.store');
        Route::get('/{id}/edit', [TransactionsController::class, 'edit'])->name('transaction.edit');
        Route::patch('/{id}/update', [TransactionsController::class, 'update'])->name('transaction.update');
        Route::delete('/{id}', [TransactionsController::class, 'destroy'])->name('transaction.delete');
    });
    Route::patch('/user', [UserController::class, 'update'])->name('user.update');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
