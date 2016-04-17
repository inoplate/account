<?php

namespace Inoplate\Account\Events\User;

class UserEmailWasUpdatedAndWaitForConfirmation
{
    /**
     * @var mixed
     */
    public $userId;

    /**
     * Create new UserEmailWasUpdatedAndWaitForConfirmation instance
     * 
     * @param mixed $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }
}