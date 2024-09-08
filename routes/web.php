<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', [UserController::class, 'index'])->name('welcome');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/search', [UserController::class, 'search'])->name('search');
Route::post('/topUpSaldo', [UserController::class, 'topUpSaldo'])->name('topUpSaldo');
Route::post('/TarikTunai', [UserController::class, 'TarikTunai'])->name('TarikTunai');
Route::get('/acceptWalletRequest/{id}', [HomeController::class, 'acceptWalletRequest'])->name('acceptWalletRequest');
Route::get('/rejectWalletRequest/{id}', [HomeController::class, 'rejectWalletRequest'])->name('rejectWalletRequest');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');