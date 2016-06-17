<?php

namespace Inoplate\Account\Domain\Repositories;

use Inoplate\Account\Domain\Models\User as Model;

interface User
{
    /**
     * Retrieve user generated identity
     * 
     * @return Inoplate\Account\Domain\Models\UserId
     */
    public function nextIdentity();
    
    /**
     * Retrieve user by id
     * 
     * @param  mixed $id
     * @return Inoplate\Account\Domain\Models\User
     */
    public function findById($id);

    /**
     * Retrieve user by email
     * 
     * @param  mixed $email
     * @return Inoplate\Account\Domain\Models\User
     */
    public function findByEmail($email);

    /**
     * Retrieve user by username
     * 
     * @param  mixed $username
     * @return Inoplate\Account\Domain\Models\User
     */
    public function findByUsername($username);

    /**
     * Save entity updates
     * 
     * @param  Model   $entity
     * @return void
     */
    public function save(Model $entity);

    /**
     * Mark the end of life of entity
     * 
     * @param  Model   $entity
     * @return void
     */
    public function remove(Model $id);
}