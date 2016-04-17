<?php

namespace Inoplate\Account\Contracts;

interface UserResolver
{
    /**
     * Resolve user
     * @param  string $userId
     * @param  User   $user
     * @return null|Authenticatable
     */
    public function resolve($userId, User $user);
}