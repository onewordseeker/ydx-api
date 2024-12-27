<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function(){
    echo (json_encode(['api_status' => 'working']));
}); // done
Route::post('import', [LoginController::class, 'import'])->name('login'); // done
Route::post('create', [LoginController::class, 'create'])->name('create'); // done
Route::get('get_settings', [LoginController::class, 'getSettings'])->name('get-settings'); // done

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('get_balance', [UserController::class, 'getAllBalances'])->name('get-balance'); // done
    Route::get('check_login', [UserController::class, 'checkLogin'])->name('checkLogin'); // done
    Route::post('export_wallet', [UserController::class, 'exportWallet'])->name('export-wallet'); // done
    Route::get('get_transactions', [UserController::class, 'getTransactions'])->name('get-transactions'); // done
    Route::post('transfer', [UserController::class, 'transfer'])->name('transfer'); // done
    Route::post('swap', [UserController::class, 'swap'])->name('swap'); // done
    Route::get('get_swap_history', [UserController::class, 'getSwapHistory'])->name('get_swap_history'); // done
});
