<?php

use App\Http\Controllers\Wallet\CreateWalletAction;
use App\Http\Controllers\Wallet\GetWalletAction;
use App\Http\Controllers\Wallet\ListWalletAction;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/api/wallets', ListWalletAction::class);
Route::get('/api/wallets/{id}', GetWalletAction::class);
Route::post('/api/wallets', CreateWalletAction::class)->withoutMiddleware(VerifyCsrfToken::class);
