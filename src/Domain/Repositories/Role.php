<?php

namespace Inoplate\Account\Domain\Repositories;

use Inoplate\Account\Domain\Models\Role as Model;

interface Role
{
    /**
     * Retrieve role generated identity
     * 
     * @return Inoplate\Account\Domain\Models\RoleId
     */
    public function nextIdentity();

    /**
     * Retrieve all role
     * 
     * @return array
     */
    public function all();

    /**
     * Retrieve role by id
     * 
     * @param  mixed $id
     * @return Inoplate\Account\Domain\Models\Role
     */
    public function findById($id);

    /**
     * Retrieve role by name
     * 
     * @param  mixed $name
     * @return Inoplate\Account\Domain\Models\Role
     */
    public function findByName($name);

    /**
     * Retrieve default roles
     * 
     * @return array
     */
    public function getDefaultRoles();

    /**
     * Save entity updates
     * 
     * @param  mixed   $entity
     * @return void
     */
    public function save(Model $entity);

    /**
     * Mark the end of life of entity
     * 
     * @param  $id
     * @return void
     */
    public function remove(Model $id);
}