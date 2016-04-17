<?php

namespace Inoplate\Account\Domain\Repositories;

use Inoplate\Account\Domain\Models\PermissionId;
use Inoplate\Account\Domain\Models\Permission as PermissionModel;

interface Permission
{
    /**
     * Retrieve all permissions
     * 
     * @return array
     */
    public function all();

    /**
     * Retrieve permission by id
     * 
     * @param  PermissionId $id
     * @return Inoplate\Account\Domain\Models\Permission
     */
    public function findById(PermissionId $id);

    /**
     * Save entity updates
     * 
     * @param  Inoplate\Account\Domain\Models\Permission   $entity
     * @return void
     */
    public function save(PermissionModel $entity);
}