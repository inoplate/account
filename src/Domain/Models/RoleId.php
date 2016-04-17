<?php

namespace Inoplate\Account\Domain\Models;

use Inoplate\Foundation\Domain\Models\ValueObject;

class RoleId extends ValueObject
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * Create new RoleId instance
     * 
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }
}