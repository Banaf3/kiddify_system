<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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
        // Define authorization gates for role-based access
        Gate::define('isAdmin', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('isTeacher', function (User $user) {
            return $user->isTeacher();
        });

        Gate::define('isStudent', function (User $user) {
            return $user->isStudent();
        });

        Gate::define('isParent', function (User $user) {
            return $user->isParent();
        });
    }
}
