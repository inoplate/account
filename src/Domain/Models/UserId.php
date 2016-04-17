<?php

namespace Inoplate\Account\Domain\Models;

use Inoplate\Foundation\Domain\Models\ValueObject;

class UserId extends ValueObject
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * Create new UserId instance
     * 
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }
}