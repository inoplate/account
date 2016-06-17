<?php

namespace Inoplate\Account\Providers;

use Auth;
use Inoplate\Account\Services\Permission\Collector as PermissionCollector;
use Inoplate\Foundation\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * @inherit_docs
     */
    protected $moduleName = 'inoplate-account::labels.title';

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(){}

    /**
     * Register permisions
     * 
     * @return array
     */
    protected function registeredPermissions()
    {
        return require __DIR__.'/../../database/collections/permissions.php';
    }
}