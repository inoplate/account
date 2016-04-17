<?php

namespace Inoplate\Account\Domain\Events;

use Inoplate\Account\Domain\Models\Role;
use Inoplate\Foundation\Domain\Event;

class RoleWasCreated extends Event
{
    /**
     * @var Inoplate\Account\Domain\Models\Role
     */
    protected $role;

    /**
     * Create new RoleWasCreated instance
     * 
     * @param Role $role
     */
    public function __construct(Role $role)
    {
        $this->role = $role;
    }
}