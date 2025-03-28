<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string|null>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function register(): void
    {
        $this->registerPolicies();
        
        // For Laravel 11, explicitly register gates in the register method
        // This ensures they're available when the app is configured
        $this->registerGates();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // No longer need to register gates here as they're registered in register()
    }

    /**
     * Register all application gates
     */
    private function registerGates(): void
    {        
        // Gate for selling advertisements
        Gate::define('sell-advertisements', function ($user) {
            return in_array($user->role, ['admin', 'seller', 'business']);
        });
    }
} 