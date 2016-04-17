<?php

namespace Inoplate\Account\Providers;

use Inoplate\Foundation\Providers\WidgetServiceProvider as ServiceProvider;

class WidgetServiceProvider extends ServiceProvider
{
    /**
     * Widgets to register
     * 
     * @var array
     */
    protected $widgets = [
        'inoplate.account.admin.dashboard.left' => [
            'Inoplate\Account\Widgets\Usercard'
        ],
    ];
}