<?php

namespace Inoplate\Account\Domain\Events;

use Inoplate\Account\Domain\Models\User;
use Inoplate\Foundation\Domain\Event;

class UserWasRegistered extends Event
{
    /**
     * @var Inoplate\Account\Domain\Models\User
     */
    protected $user;

    /**
     * Create new UserWasRegistered instance
     * 
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}