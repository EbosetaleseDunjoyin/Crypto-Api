<?php

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

Route::middleware('web')->group(function () {
    Route::get('/documentation', function () {
         return view('documentation');
     });
});


Route::get('login', function () {
    return response()->json([
        'status' => false,
        'message' => 'Unauthorised',
    ], 401);
})->name('login');
