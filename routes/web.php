<?php

use App\Http\Controllers\PageController;
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

// Route::middleware('bypassCsrf')->group(function () {

// });
Route::get('documentation', [PageController::class, 'documentation_page']);


Route::get('login', [PageController::class, 'login_page'])->name('login');
