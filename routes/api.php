<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryInController;
use App\Http\Controllers\MoveController;
use App\Http\Controllers\LocationInventoryController;
use App\Http\Controllers\BulkReceiveController;
use App\Http\Controllers\InventoryUndoController;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/inventory/in', [InventoryInController::class, 'store']);
    Route::post('/inventory/move', [MoveController::class, 'store']);
    Route::post('/inventory/bulk-in', [BulkReceiveController::class, 'store']);
    Route::post('/inventory/undo', [InventoryUndoController::class, 'store']);
});
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/locations/{location}', [LocationInventoryController::class, 'show']);
