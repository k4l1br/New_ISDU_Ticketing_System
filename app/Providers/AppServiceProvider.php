<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define Gates for role-based access
        Gate::define('super_admin', function ($user) {
            return $user->role === 'super_admin';
        });

        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('admin_or_super_admin', function ($user) {
            return in_array($user->role, ['admin', 'super_admin']);
        });
    }
}
