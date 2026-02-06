<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryInController;
use App\Http\Controllers\MoveController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationInventoryController;
use App\Http\Controllers\InventoryUndoController;
use App\Http\Controllers\InventoryExportController;
use App\Http\Controllers\AuthController;

Route::get('/login', function () {
    return response()->json(['message' => 'Unauthenticated.'], 401);
})->name('login');



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/inventory/in', [InventoryInController::class, 'store']);
    Route::post('/inventory/move', [MoveController::class, 'store']);
    Route::post('/inventory/move/bulk', [MoveController::class, 'bulkMove']);
    Route::post('/inventory/bulk-in', [InventoryInController::class, 'bulkStore']);
    Route::post('/inventory/undo', [InventoryUndoController::class, 'store']);
    Route::get('/inventory/logs/export', [InventoryExportController::class, 'logs']);
    Route::get('/inventory/stock/export', [InventoryExportController::class, 'stock']);
    Route::get('/locations/{location}', [LocationInventoryController::class, 'show']);
});
Route::get('/dashboard', [DashboardController::class, 'index']);