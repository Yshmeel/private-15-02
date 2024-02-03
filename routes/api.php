<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/user', [\App\Http\Controllers\ApiController::class, 'createUser']);

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/routine', [\App\Http\Controllers\ApiController::class, 'routine']);
    Route::patch('/select-room', [\App\Http\Controllers\ApiController::class, 'selectRoom']);
    Route::post('/send-message', [\App\Http\Controllers\ApiController::class, 'sendMessage']);
    Route::delete('/user', [\App\Http\Controllers\ApiController::class, 'logout']);
});
