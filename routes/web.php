<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryInController;
use App\Http\Controllers\MoveController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationInventoryController;
use App\Http\Controllers\BulkReceiveController;
use App\Http\Controllers\InventoryUndoController;

Route::get('/login', function () {
    return response()->json(['message' => 'Unauthenticated.'], 401);
})->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/inventory/in', [InventoryInController::class, 'store']);
    Route::post('/inventory/move', [MoveController::class, 'store']);
    Route::post('/inventory/bulk-in', [BulkReceiveController::class, 'store']);
    Route::post('/inventory/undo', [InventoryUndoController::class, 'store']);
    Route::get('/locations/{location}', [LocationInventoryController::class, 'show']);
});
Route::get('/dashboard', [DashboardController::class, 'index']);
