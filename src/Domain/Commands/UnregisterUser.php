<?php

namespace Inoplate\Account\Domain\Commands;

use Inoplate\Foundation\Domain\Command;

class UnregisterUser extends Command
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * Create new UnregisterUser instance
     * 
     * @param mixed $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }
}