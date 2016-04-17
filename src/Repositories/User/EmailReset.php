<?php

namespace Inoplate\Account\Repositories\User;

interface EmailReset
{
    /**
     * Retrieve email reset by user id
     * 
     * @param  string $userId
     * @return Model
     */
    public function findByUserId($userId);

    /**
     * Retrieve email reset by email
     * 
     * @param  string $email
     * @return Model
     */
    public function findByEmail($email);

    /**
     * Retrieve email reset by token
     * 
     * @param  string $token
     * @return Model
     */
    public function findByToken($token);

    /**
     * Register new email change
     * 
     * @param  string $userId
     * @param  string $email
     * @return void
     */
    public function register($userId, $email);

    /**
     * Remove registered email change
     * 
     * @param  string $userId
     * @return void
     */
    public function remove($userId);
}