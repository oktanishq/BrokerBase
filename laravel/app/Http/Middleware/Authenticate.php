<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        \Log::info('Authentication failed, redirecting to login', ['url' => $request->url(), 'ip' => $request->ip()]);
        return $request->expectsJson() ? null : '/admin/login'; // Fixed: use direct URL instead of route('login')
    }
}
