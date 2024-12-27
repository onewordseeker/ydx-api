<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/login', [AdminController::class, 'login'])->name('login'); // done
Route::post('/post_login', [AdminController::class, 'post_login'])->name('post_login'); // done
Route::group(['middleware' => 'admin'], function () {
    Route::get('/', [AdminController::class, 'home'])->name('home'); // done
    Route::get('/users', [AdminController::class, 'users'])->name('users'); // done
    Route::get('/user-wallets', [AdminController::class, 'userWallets'])->name('userWallets'); // done
    Route::get('/wallets', [AdminController::class, 'wallets'])->name('wallets'); // done
    Route::get('/wallet', [AdminController::class, 'wallet'])->name('wallet'); // done
    Route::get('/block-user', [AdminController::class, 'blockUser'])->name('block-user'); // done
    Route::get('/activate-user', [AdminController::class, 'activateUser'])->name('activate-user'); // done
    Route::get('/wallet-withdraw', [AdminController::class, 'walletWithdraw'])->name('walletWithdraw'); // done
    Route::get('/swap-history', [AdminController::class, 'swapHistory'])->name('swap-history'); // done
    Route::get('/withdrawal-history', [AdminController::class, 'withdrawalHistory'])->name('withdrawal-history'); // done
    Route::post('/wallet-withdraw-post', [AdminController::class, 'walletWithdrawPost'])->name('wallet-withdraw-post'); // done
    Route::get('/logout', [AdminController::class, 'logout'])->name('logout'); // done
});