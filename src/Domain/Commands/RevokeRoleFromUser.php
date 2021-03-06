<?php

namespace Inoplate\Account\Domain\Commands;

use Inoplate\Foundation\Domain\Command;

class RevokeRoleFromUser extends Command
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var mixed
     */
    protected $roleId;

    /**
     * Create new RevokeRoleFromUser instance
     * 
     * @param mixed $id       
     * @param mixed $roleId
     */
    public function __construct($id, $roleId)
    {
        $this->id = $id;
        $this->roleId = $roleId;
    }
}