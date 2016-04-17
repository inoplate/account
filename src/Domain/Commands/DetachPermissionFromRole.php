<?php

namespace Inoplate\Account\Domain\Commands;

use Inoplate\Foundation\Domain\Command;

class DetachPermissionFromRole extends Command
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var mixed
     */
    protected $permissionId;

    /**
     * Create new DetachPermissionFromRole instance
     * 
     * @param mixed $id
     * @param mixed $permissionId
     */
    public function __construct($id, $permissionId)
    {
        $this->id = $id;
        $this->permissionId = $permissionId;
    }
}