<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


// Route::group(['middleware' => ['auth:api']], function () {

    Route::group(['prefix' => 'users'], function () {
        Route::get('', [UserController::class, 'all']);
        Route::get('/{id}', [UserController::class, 'find']);
        Route::post('', [UserController::class, 'save']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::patch('/{id}', [UserController::class, 'changeField']);
        Route::delete('/{id}', [UserController::class, 'delete']);
    });

    Route::group(['prefix' => 'role'], function () {
        Route::get('', [RoleController::class, 'all']);
    });
    
// });
