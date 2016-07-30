<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    const AUTH_ADMIN_ROLE = 1;
    const AUTH_EDITOR_ROLE = 3;

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define('auth', function ($user) {
            return !empty($user);
        });

        $gate->define('admin', function ($user) {
            return !empty($user) && self::AUTH_ADMIN_ROLE == $user->role;
        });

        $gate->define('editor', function ($user) {
            if (empty($user)) {
                return false;
            }

            if (self::AUTH_ADMIN_ROLE == $user->role || self::AUTH_EDITOR_ROLE == $user->role) {
                return true;
            }

            return false;
        });
    }
}
