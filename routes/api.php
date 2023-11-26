<?php

use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
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

// Register and Login

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'storeToken']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [LoginController::class, 'revokeToken']);

    // Tasks actions
    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index']);
        Route::get('/{task}', [TaskController::class, 'show']);
        Route::post('/', [TaskController::class, 'store']);
        Route::put('/{task}', [TaskController::class, 'update']);
        Route::put('/{task}/update-status', [TaskController::class, 'updateStatus']);
        Route::delete('/{task}', [TaskController::class, 'destroy']);
    });
});
