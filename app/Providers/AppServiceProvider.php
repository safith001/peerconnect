<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // leave empty (or your bindings)
    }

    public function boot(): void
    {
        // leave empty
    }
}
