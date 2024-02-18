<?php

use App\Http\Controllers\TransacaoController;
use Illuminate\Support\Facades\Route;
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

Route::post('/login', [LoginController::class, 'login']);

Route::middleware('autenticar')->group(function() {
    Route::controller(TransacaoController::class)->group(function() {
        Route::post('/transferir', 'transferir');
    });
});
