<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AdminServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::middleware(['web', 'auth', 'admin'])
            ->prefix('admin')
            ->name('admin.')
            ->group(__DIR__.'/../../admin/routes.php');

        $this->loadViewsFrom(__DIR__.'/../../admin/views', 'admin');
    }
}
