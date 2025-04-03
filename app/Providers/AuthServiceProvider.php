<?php

namespace App\Providers;

use App\Consts\UserConsts;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        // 管理者
        Gate::define('system-admin', function($user) {
            return ($user->role == UserConsts::ROLE_SYSTEM_ADMIN);
        });
        Gate::define('user', function($user) {
            return ($user->role == UserConsts::ROLE_USER);
        });
    }
}