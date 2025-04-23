<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LicitacionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TrmController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


// Route::group(['middleware' => ['auth:api']], function () {

    Route::post('refresh-token', [AuthController::class, 'refresh']);

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

    Route::group(['prefix' => 'licitaciones'], function () {
        Route::post('/sincronizar-licitaciones', [LicitacionController::class, 'sincronizarLicitaciones']); // Recibimos 'cantidad' como parámetro
    });

    Route::group(['prefix' => 'trm'], function () {
        Route::post('/sincronizar-trms', [TrmController::class, 'sincronizarTrm']); // Recibimos 'cantidad' como parámetro
    });
// });
