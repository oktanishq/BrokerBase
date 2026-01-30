<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/property/{id}', [PropertyController::class, 'show'])->name('property.show');

Route::get('/admin/login', function () {
    return view('admin.login');
});

Route::post('/admin/login', [LoginController::class, 'login']);

Route::get('/admin', function () {
    if (Auth::check()) {
        return redirect('/admin/dashboard');
    }
    return redirect('/admin/login');
})->name('admin.index');

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/inventory', function () {
        return view('admin.inventory');
    })->name('admin.inventory');

    Route::get('/admin/leads', function () {
        return view('admin.leads');
    })->name('admin.leads');

    Route::get('/admin/analytics', function () {
        return view('admin.analytics');
    })->name('admin.analytics');

    Route::get('/admin/settings', [SettingsController::class, 'edit']);
    Route::post('/admin/settings/update', [SettingsController::class, 'update']);

    // Property Management Routes
    Route::get('/admin/properties/create', function () {
        return view('admin.properties.create');
    })->name('properties.create');
    Route::post('/admin/properties/draft', [AdminPropertyController::class, 'storeDraft'])->name('properties.draft');
    Route::post('/admin/properties', [AdminPropertyController::class, 'store'])->name('properties.store');

    // Logout Route
    Route::post('/logout', [LoginController::class, 'logout']);
});
