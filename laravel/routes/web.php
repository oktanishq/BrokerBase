<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;

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

Route::get('/admin/login', function () {
    return view('admin.login');
});

Route::post('/admin/login', [LoginController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    });

    Route::get('/admin/inventory', function () {
        return view('admin.inventory');
    });

    Route::get('/admin/leads', function () {
        return view('admin.leads');
    });

    Route::get('/admin/analytics', function () {
        return view('admin.analytics');
    });

    Route::get('/admin/settings', function () {
        return view('admin.settings');
    });

    // Logout Route
    Route::post('/logout', [LoginController::class, 'logout']);
});
