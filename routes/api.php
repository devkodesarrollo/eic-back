<?php

use App\Http\Controllers\AuthController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


// Route::group(['middleware' => ['auth:api']], function () {

    Route::group(['prefix' => 'users'], function () {
        Route::get('', [AuthController::class, 'all']);
        
    });
    
// });
