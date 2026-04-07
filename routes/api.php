<?php

use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HistoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);

        Route::get('user', function (Illuminate\Http\Request $request) {
            return $request->user();
        });

        Route::post('attendance', [AttendanceController::class, 'store']); 

        Route::get('history', [HistoryController::class, 'index']);
        
        Route::get('report/excel', [HistoryController::class, 'exportExcel']);
    });
});


