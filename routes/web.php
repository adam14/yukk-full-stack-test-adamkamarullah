<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [UsersController::class, 'index'])->name('index');
Route::get('/register', [UsersController::class, 'register'])->name('register');
Route::post('/access-login', [UsersController::class, 'accessLogin'])->name('accessLogin');
Route::post('/register-save', [UsersController::class, 'registerSave'])->name('registerSave');

Route::group([
    'middleware' => 'auth'
], function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('home');
    Route::get('/logout', [UsersController::class, 'logout'])->name('logout');

    Route::group([
        'prefix' => 'transaction'
    ], function() {
        Route::get('/', [TransactionsController::class, 'index'])->name('transaction');
        Route::get('/get-history-transaction', [TransactionsController::class, 'getData'])->name('historyTransactionGetData');
        Route::post('/process-transaction', [TransactionsController::class, 'processTransaction'])->name('processTransaction');
    });
});
