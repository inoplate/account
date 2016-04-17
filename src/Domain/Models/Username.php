<?php

namespace Inoplate\Account\Domain\Models;

use Inoplate\Foundation\Domain\Models\ValueObject;
use Inoplate\Foundation\Domain\Contracts\SpecificationCandidate;

class Username extends ValueObject implements SpecificationCandidate
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }
}