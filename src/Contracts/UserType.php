<?php

namespace Inoplate\Account\Contracts;

interface UserType
{
    /**
     * Retrieve user identifier
     * @return string
     */
    public function id();

    /**
     * Retrieve user table
     * @return string
     */
    public function table();
}