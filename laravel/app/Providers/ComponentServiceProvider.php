<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ComponentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register custom component paths
        $this->registerComponentPaths();
    }

    /**
     * Register custom component directories
     */
    private function registerComponentPaths(): void
    {
        // Add public_components path to view finder so @include('public_components.filename') works
        $this->app['view']->addNamespace('public_components', base_path('resources/views/public_components'));
    }
}