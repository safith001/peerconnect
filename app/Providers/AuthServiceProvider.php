<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Conversation::class => \App\Policies\ConversationPolicy::class,
    ];

    public function boot(): void
    {
        // For Laravel 11+, this is usually enough; policies are auto-registered from $policies
    }
}
