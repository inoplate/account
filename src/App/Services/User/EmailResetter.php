<?php

namespace Inoplate\Account\App\Services\User;

interface EmailResetter
{
    /**
     * Reset email
     * 
     * @param  string $userId
     * @param  string $email
     * @return void
     */
    public function reset($userId, $email);
}