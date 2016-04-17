<?php

namespace Inoplate\Account\Domain\Repositories;

use Inoplate\Account\Domain\Models\RoleId;
use Inoplate\Account\Domain\Models\Role as RoleModel;
use Inoplate\Foundation\Domain\Models as FoundationModels;

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
     * @param  RoleId $id
     * @return Inoplate\Account\Domain\Models\Role
     */
    public function findById(RoleId $id);

    /**
     * Retrieve role by name
     * 
     * @param  Inoplate\Foundation\Domain\Models\Name $name
     * @return Inoplate\Account\Domain\Models\Role
     */
    public function findByName(FoundationModels\Name $name);

    /**
     * Retrieve default roles
     * 
     * @return array
     */
    public function getDefaultRoles();

    /**
     * Save entity updates
     * 
     * @param  RoleModel   $entity
     * @return void
     */
    public function save(RoleModel $entity);

    /**
     * Mark the end of life of entity
     * 
     * @param  RoleModel   $entity
     * @return void
     */
    public function remove(RoleModel $entity);
}