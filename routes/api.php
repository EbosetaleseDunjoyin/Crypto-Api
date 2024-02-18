<?php

use Illuminate\Http\Request;
use App\Models\CryptoAccount;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\CryptoAccountController;

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

Route::prefix("v1")->group(function(){
    Route::get('test-api',[UserAuthController::class, 'TestAPI']);
    Route::post('userRegister', [UserAuthController::class, 'register']);
    Route::post('userLogin', [UserAuthController::class, 'login']);
    Route::post('resetPassword', [UserAuthController::class, 'resetPassword']);
    
    Route::middleware('auth:sanctum')->group( function () {

        //Crypto Account Endpoints
        Route::get('coins/list',[CryptoAccountController::class, 'get_coins']);
        Route::post('coins/transfer',[CryptoAccountController::class, 'send_crypto_coin']);

        Route::post('coins/swap',[CryptoAccountController::class, 'swap_crypto_coin']);
        Route::post('coins/cryptoAccount/create',[CryptoAccountController::class, 'create_crypto_account']);

        




        //User routes
        Route::get('user/{user}',[UserController::class,'get_user']);

        Route::post('logout', [UserAuthController::class, 'logout']);
    });
});

