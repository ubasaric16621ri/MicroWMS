<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryInController;
use App\Http\Controllers\MoveController;
use App\Http\Controllers\LocationInventoryController;

Route::post('/inventory/in', [InventoryInController::class, 'store']);
Route::post('/inventory/move', [MoveController::class, 'store']);
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/locations/{location}', [LocationInventoryController::class, 'show']);
