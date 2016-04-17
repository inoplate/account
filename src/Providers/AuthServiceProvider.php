<?php

namespace Inoplate\Account\Providers;

use Auth;
use Inoplate\Account\Services\Permission\Collector as PermissionCollector;
use Inoplate\Foundation\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Booting service
     *
     * @param  Inoplate\Services\Permission\Collector $collector
     * @return void
     */
    public function boot(PermissionCollector $collector)
    {
        Auth::provider('account', function($app, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\UserProvider...
            return $app['Inoplate\Account\Domain\Repositories\User'];
        });

        parent::boot($collector);
    }

    public function register(){}

    /**
     * Register permisions
     * 
     * @return array
     */
    protected function registerPermissions()
    {
        return require __DIR__.'/../../database/collections/permissions.php';
    }

    /**
     * Register permissions aliases
     * 
     * @return array
     */
    protected function registerPermissionsAliases()
    {
        return require __DIR__.'/../../database/collections/permissions_aliases.php';
    }
}