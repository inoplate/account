<?php

namespace Inoplate\Account\Domain\Models;

use Inoplate\Foundation\Domain\Models\ValueObject;

class PermissionId extends ValueObject
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * Create new PermissionId instance
     * 
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }
}