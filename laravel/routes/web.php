<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

Route::post('/admin/login', function (Request $request) {
    if ($request->email && $request->password) {
        return redirect('/admin/dashboard');
    }
    return back()->with('error', 'Invalid credentials');
});

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

// Logout Route
Route::post('/logout', function () {
    // In a real application, this would logout the user
    // For demo purposes, just redirect to login
    return redirect('/admin/login');
});
