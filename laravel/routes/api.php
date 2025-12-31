<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\PropertyController;

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

// Property Management API routes (Web Form Authentication)
Route::middleware(['auth', 'web'])->group(function () {
    // Property CRUD operations
    Route::get('/admin/properties', [AdminPropertyController::class, 'index']);
    Route::post('/admin/properties', [AdminPropertyController::class, 'store']);
    Route::get('/admin/properties/{property}', [AdminPropertyController::class, 'show']);
    Route::match(['put', 'patch'], '/admin/properties/{property}', [AdminPropertyController::class, 'update']);
    Route::delete('/admin/properties/{property}', [AdminPropertyController::class, 'destroy']);
    
    // Draft operations
    Route::post('/admin/properties/draft', [AdminPropertyController::class, 'storeDraft']);
    
    // Status management
    Route::put('/admin/properties/{property}/status', [AdminPropertyController::class, 'updateStatus']);
});

// Property Management API routes (SPA/Mobile Authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Property CRUD operations for API clients
    Route::get('/admin/properties-spa', [AdminPropertyController::class, 'index']);
    Route::post('/admin/properties-spa', [AdminPropertyController::class, 'store']);
    Route::get('/admin/properties-spa/{property}', [AdminPropertyController::class, 'show']);
    Route::match(['put', 'patch'], '/admin/properties-spa/{property}', [AdminPropertyController::class, 'update']);
    Route::delete('/admin/properties-spa/{property}', [AdminPropertyController::class, 'destroy']);
});

// Public Property API routes (no authentication required)
Route::get('/properties', [PropertyController::class, 'getProperties']);
Route::get('/properties/{id}', [PropertyController::class, 'getProperty']);
