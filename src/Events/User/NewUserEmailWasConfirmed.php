<?php

namespace Inoplate\Account\Events\User;

class NewUserEmailWasConfirmed
{
    /**
     * @var mixed
     */
    public $userId;

    /**
     * Create new NewUserEmailWasConfirmed instance
     * 
     * @param mixed $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }
}