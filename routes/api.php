<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HewanController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        Route::get('types', [TypeController::class, 'Index']);
        Route::get('/types/{id}', [TypeController::class, 'show']);
        Route::post('/types', [TypeController::class, 'store']);
        Route::put('/types/{id}', [TypeController::class, 'update']);
        Route::delete('/types/{id}', [TypeController::class, 'destroy']);

        Route::post('/hewans', [HewanController::class, 'store']);
        Route::put('/hewans/{id}', [HewanController::class, 'update']);
        Route::delete('/hewans/{id}', [HewanController::class, 'destroy']);

    });

    Route::middleware('role:admin,user')->group(function () {
        Route::get('/profile', [UserController::class, 'profile']);
        Route::put('/profile', [UserController::class, 'updateProfile']);
        Route::put('/profile/password', [UserController::class, 'updatePassword']);

        Route::get('/hewans/search/{keyword}', [HewanController::class, 'search']);

        Route::get('/hewans', [HewanController::class, 'index']);
        Route::get('/hewans/{id}', [HewanController::class, 'show']);

    });

    Route::middleware('role:user')->group(function () {

    });
});