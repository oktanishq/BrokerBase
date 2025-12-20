<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        \Log::info('Login attempt', ['email' => $request->email, 'ip' => $request->ip()]);

        // Validate the input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            \Log::warning('Login validation failed', ['errors' => $validator->errors()->toArray()]);
            return back()->withErrors($validator)->withInput();
        }

        // Attempt to authenticate the user
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            // Authentication successful, redirect to dashboard
            $request->session()->regenerate();
            \Log::info('Login successful', ['email' => $request->email, 'user_id' => Auth::id()]);
            return redirect()->intended('/admin/dashboard');
        }

        // Authentication failed
        \Log::warning('Login failed - invalid credentials', ['email' => $request->email]);
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    /**
     * Handle the logout request.
     */
    public function logout(Request $request)
    {
        \Log::info('Logout initiated', ['user_id' => Auth::id(), 'ip' => $request->ip()]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        \Log::info('Logout completed, session cleared');
        return redirect('/admin/login');
    }
}