<?php

namespace Inoplate\Account\Domain\Commands;

use Inoplate\Foundation\Domain\Command;

class AttachPermissionToRole extends Command
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
     * Create new AttachPermissionToRole instance
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