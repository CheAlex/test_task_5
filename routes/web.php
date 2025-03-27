<?php

use App\Http\Controllers\Wallet\CreateWalletAction;
use App\Http\Controllers\Wallet\GetWalletAction;
use App\Http\Controllers\Wallet\ListWalletAction;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/wallets', ListWalletAction::class);
Route::get('/api/wallets/{id}', GetWalletAction::class);
Route::post('/api/wallets', CreateWalletAction::class)->withoutMiddleware(VerifyCsrfToken::class);
//Route::api

//Route::post('/api/wallets', function(Request $request) {
//    return $request->all();
//})->withoutMiddleware(VerifyCsrfToken::class);

//VerifyCsrfToken::class;


//Route::apiResource('/api/wallets', CreateWalletAction::class);
