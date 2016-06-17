<?php

namespace Inoplate\Account\Providers;

use Inoplate\Foundation\Providers\AppServiceProvider as ServiceProvider;
use Inoplate\Account\User;
use Inoplate\Account\Role;
use Inoplate\Account\ModelObservers;
use Inoplate\Foundation\App\Services\Events\Dispatcher as Events;

class AccountServiceProvider extends ServiceProvider
{   
    /**
     * @var array
     */
    protected $providers = [
        'Inoplate\Account\Providers\AuthServiceProvider',
        'Inoplate\Account\Providers\RouteServiceProvider',
        'Inoplate\Account\Providers\CommandServiceProvider',
        'Inoplate\Account\Providers\ConsoleServiceProvider',
        'Inoplate\Account\Providers\WidgetServiceProvider',
    ];

    /**
     * Boot package
     * 
     * @return void
     */
    public function boot(Events $events)
    {
        $this->loadPublic();
        $this->loadView();
        $this->loadTranslation();
        $this->loadConfiguration();
        $this->loadMigration();

        User::Observe(new ModelObservers\UserObserver());
        Role::Observe(new ModelObservers\RoleObserver($events));

        $this->app['navigation']->register(require __DIR__ .'/../Http/navigations.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Repositories binding
        $this->app->singleton('Inoplate\Account\Domain\Repositories\Permission', 'Inoplate\Account\Infrastructure\Repositories\InMemoryPermission');
        $this->app->bind('Inoplate\Account\Domain\Repositories\Role', 'Inoplate\Account\Infrastructure\Repositories\EloquentRole');
        $this->app->bind('Inoplate\Account\Domain\Repositories\User', 'Inoplate\Account\Infrastructure\Repositories\EloquentUser');
        $this->app->bind('Inoplate\Account\Repositories\User\EmailReset', 'Inoplate\Account\Infrastructure\Repositories\EloquentEmailReset');

        // Services binding
        $this->app->bind('Inoplate\Account\App\Services\User\EmailResetter', 'Inoplate\Account\Services\User\ReconfirmEmailResetter');

        parent::register();
    }

    /**
     * Publish public assets
     * @return void
     */
    protected function loadPublic()
    {
        $this->publishes([
            __DIR__.'/../../public' => public_path('vendor/inoplate-account'),
        ], 'public');
    }

    /**
     * Load package's views
     * 
     * @return void
     */
    protected function loadView()
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'inoplate-account');

        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/inoplate-account'),
        ], 'views');
    }

    /**
     * Load packages's translation
     * 
     * @return void
     */
    protected function loadTranslation()
    {
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'inoplate-account');
    }

    /**
     * Load packages migration
     * 
     * @return void
     */
    protected function loadMigration()
    {
        $this->publishes([
            __DIR__.'/../../database/migrations/' => database_path('migrations')
        ], 'migrations');
    }

    /**
     * Load package configuration
     * 
     * @return void
     */
    protected function loadConfiguration()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/account.php', 'inoplate.account'
        );

        $this->publishes([
            __DIR__.'/../../config/account.php' => config_path('inoplate/account.php'),
        ], 'config');
    }
}