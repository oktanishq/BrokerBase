<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\PropertyController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Settings API routes
Route::get('/settings', [SettingsController::class, 'getSettings']);

// Property Management API routes
Route::middleware('auth:sanctum')->group(function () {
    // Property CRUD operations
    Route::get('/admin/properties', [PropertyController::class, 'index']);
    Route::post('/admin/properties', [PropertyController::class, 'store']);
    Route::get('/admin/properties/{property}', [PropertyController::class, 'show']);
    Route::put('/admin/properties/{property}', [PropertyController::class, 'update']);
    Route::delete('/admin/properties/{property}', [PropertyController::class, 'destroy']);
    
    // Draft operations
    Route::post('/admin/properties/draft', [PropertyController::class, 'storeDraft']);
    
    // Status management
    Route::put('/admin/properties/{property}/status', [PropertyController::class, 'updateStatus']);
});
