<?php

namespace Inoplate\Account\Domain\Commands;

use Inoplate\Foundation\Domain\Command;

class DeleteRole extends Command
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * Create new DeleteRole instance
     * 
     * @param mixed $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }
}