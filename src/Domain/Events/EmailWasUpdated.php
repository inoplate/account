<?php

namespace Inoplate\Account\Domain\Events;

use Inoplate\Account\Domain\Models\User;
use Inoplate\Foundation\Domain\Event;

class EmailWasUpdated extends Event
{
    /**
     * @var Inoplate\Account\Domain\Models\User
     */
    protected $user;

    /**
     * Create new EmailWasUpdated instance
     * 
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}