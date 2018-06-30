<?php

namespace App\Providers;

use App\Extensions\AuthUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('userService', function ($app, array $config) {
            return new AuthUserProvider();
        });

        // 鉴定访问权限
        Gate::define('platform', 'App\Policies\BusinessPrivilegePolicy@platformAuth');
        Gate::define('appDevelop', 'App\Policies\BusinessPrivilegePolicy@appDevelopAuth');
        Gate::define('appManage', 'App\Policies\BusinessPrivilegePolicy@appManageAuth');
        Gate::define('appContent', 'App\Policies\BusinessPrivilegePolicy@appContentAuth');
    }
}
