<?php

namespace Inoplate\Account\Providers;

use Inoplate\Foundation\Providers\ConsoleServiceProvider as ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * Commands to register
     * 
     * @var array
     */
    protected $commands = [
        \Inoplate\Account\Console\Commands\Init::class,
        \Inoplate\Account\Console\Commands\AttachAllPermissions::class,
        \Inoplate\Account\Console\Commands\DetachAllPermissions::class,
    ];
}