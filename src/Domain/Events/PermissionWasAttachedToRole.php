<?php

namespace Inoplate\Account\Domain\Events;

use Inoplate\Account\Domain\Models\Permission;
use Inoplate\Account\Domain\Models\Role;
use Inoplate\Foundation\Domain\Event;

class PermissionWasAttachedToRole extends Event
{
    /**
     * @var Inoplate\Account\Domain\Models\Role
     */
    protected $role;

    /**
     * @var Inoplate\Account\Domain\Models\Permission
     */
    protected $permission;

    /**
     * Create new PermissionWasAttachedToRole instance
     * 
     * @param Role       $role
     * @param Permission $permission
     */
    public function __construct(Role $role, Permission $permission)
    {
        $this->role = $role;
        $this->permission = $permission;
    }
}