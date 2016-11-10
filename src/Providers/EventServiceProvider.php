<?php

namespace Inoplate\Account\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    
    protected $listen = [
        'Inoplate\Account\Events\User\UserEmailWasUpdatedAndWaitForConfirmation' => [
            'Inoplate\Account\Listeners\User\EmailUserForEmailConfirmation',
        ],
    ];
}
