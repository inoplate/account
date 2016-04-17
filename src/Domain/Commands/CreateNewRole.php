<?php

namespace Inoplate\Account\Domain\Commands;

use Inoplate\Foundation\Domain\Command;

class CreateNewRole extends Command
{
    /**
     * @var mixed
     */
    protected $name;

    /**
     * @var array
     */
    protected $description;

    /**
     * Create new CreateNewRole instance
     * 
     * @param mixed $name
     * @param array $description
     */
    public function __construct($name, $description = [])
    {
        $this->name = $name;
        $this->description = $description;
    }
}