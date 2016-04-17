<?php

namespace Inoplate\Account\Domain\Events;

use Inoplate\Account\Domain\Models\User;
use Inoplate\Account\Domain\Models\Role;
use Inoplate\Foundation\Domain\Event;

class RoleWasRevokedFromUser extends Event
{
    /**
     * @var Inoplate\Account\Domain\Models\User
     */
    protected $user;

    /**
     * @var Inoplate\Account\Domain\Models\Role
     */
    protected $role;

    /**
     * Create new RoleWasRevokedFromUser instance
     * 
     * @param User $user
     * @param Role $role
     */
    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;
    }
}