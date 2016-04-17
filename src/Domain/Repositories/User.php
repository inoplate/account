<?php

namespace Inoplate\Account\Domain\Repositories;

use Inoplate\Account\Domain\Models\UserId;
use Inoplate\Account\Domain\Models\Username;
use Inoplate\Account\Domain\Models\User as UserModel;
use Inoplate\Foundation\Domain\Models as FoundationModels;

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
     * @param  UserId $id
     * @return Inoplate\Account\Domain\Models\User
     */
    public function findById(UserId $id);

    /**
     * Retrieve user by email
     * 
     * @param  Inoplate\Foundation\Domain\Models\Email $email
     * @return Inoplate\Account\Domain\Models\User
     */
    public function findByEmail(FoundationModels\Email $email);

    /**
     * Retrieve user by username
     * 
     * @param  Username $username
     * @return Inoplate\Account\Domain\Models\User
     */
    public function findByUsername(Username $username);

    /**
     * Save entity updates
     * 
     * @param  UserModel   $entity
     * @return void
     */
    public function save(UserModel $entity);

    /**
     * Mark the end of life of entity
     * 
     * @param  UserModel   $entity
     * @return void
     */
    public function remove(UserModel $entity);
}