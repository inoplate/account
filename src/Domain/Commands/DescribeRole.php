<?php

namespace Inoplate\Account\Domain\Commands;

use Inoplate\Foundation\Domain\Command;

class DescribeRole extends Command
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var name
     */
    protected $name;

    /**
     * @var array
     */
    protected $description;

    /**
     * Create new DescribeRole instance
     * 
     * @param mixed $id       
     * @param mixed $name 
     * @param array $description
     */
    public function __construct($id, $name, $description = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }
}