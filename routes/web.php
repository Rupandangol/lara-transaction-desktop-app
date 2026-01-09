<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TempTransactionController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\UploadTransactionImageController;
use App\Http\Controllers\UserAiKeyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => '/transactions'], function () {
        Route::get('/', [TransactionsController::class, 'index'])->name('transaction.index');
        Route::post('/import', [TransactionsController::class, 'import'])->name('transaction.import');
        Route::get('/sample', [TransactionsController::class, 'sample'])->name('transaction.sample');
        Route::delete('/purge', [TransactionsController::class, 'purge'])->name('transaction.purge');
        Route::get('/create', [TransactionsController::class, 'create'])->name('transaction.create');
        Route::get('/temp-transaction', [TempTransactionController::class, 'index'])->name('temp.transaction.index');
        Route::delete('/temp-transaction/{id}', [TempTransactionController::class, 'destroy'])->name('temp.transaction.destroy');
        Route::get('/temp-transaction-approve', [TempTransactionController::class, 'approve'])->name('temp.transaction.approve');
        Route::get('/upload-transaction', [UploadTransactionImageController::class, 'index'])->name('transaction.upload.transaction.image.index');
        Route::post('/upload-transaction', [UploadTransactionImageController::class, 'store'])->name('transaction.upload.transaction.image.store');
        Route::get('/export', [TransactionsController::class, 'export'])->name('transaction.export');
        Route::post('/store', [TransactionsController::class, 'store'])->name('transaction.store');
        Route::get('/{id}/edit', [TransactionsController::class, 'edit'])->name('transaction.edit');
        Route::patch('/{id}/update', [TransactionsController::class, 'update'])->name('transaction.update');
        Route::delete('/{id}', [TransactionsController::class, 'destroy'])->name('transaction.delete');
        Route::get('/admins', [AdminController::class, 'index'])->name('admins.index');
        Route::delete('/admins/{id}', [AdminController::class, 'destroy'])->name('admins.destroy');
    });
    Route::patch('/user', [UserController::class, 'update'])->name('user.update');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::group(['prefix' => '/user-ai-key'], function () {
        Route::get('/', [UserAiKeyController::class, 'index'])->name('user-ai-key.index');
        Route::post('/', [UserAiKeyController::class, 'store'])->name('user-ai-key.store');
        Route::patch('/{id}', [UserAiKeyController::class, 'update'])->name('user-ai-key.update');
    });
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
