<?php

namespace Inoplate\Account\Infrastructure\Repositories;

use Inoplate\Account\Domain\Models as AccountDomainModels;
use Inoplate\Foundation\Domain\Models as FoundationDomainModels;
use Inoplate\Account\Domain\Repositories\Permission as Contract;

class InMemoryPermission implements Contract
{
    /**
     * @var array
     */
    protected $permissions = [];

    /**
     * Retrieve all permissions
     * 
     * @return array
     */
    public function all()
    {
        $permissions = [];

        foreach ($this->permissions as $permission) {
            $permissions[] = $this->toDomainModel($permission);
        }

        return $permissions;
    }

    /**
     * Retrieve permission by id
     * 
     * @param  PermissionId $id
     * @return null|Inoplate\Account\Domain\Models\Permission
     */
    public function findById(AccountDomainModels\PermissionId $id)
    {
        $key = array_search($id->value(), array_column($this->permissions, 'id'));

        if($key === false) {
            return null;
        }
        
        return $this->toDomainModel($this->permissions[$key]);
    }

    /**
     * Save entity updates
     * 
     * @param  Inoplate\Account\Domain\Models\Permission   $entity
     * @return void
     */
    public function save(AccountDomainModels\Permission $entity)
    {
        $id = $entity->id()->value();
        $description = $entity->description()->value();

        $permission['id'] = $id;

        foreach ($description as $key => $value) {
            $permission[$key] = $value;
        }

        $key = array_search($id, array_column($this->permissions, 'id'));

        if($key === false) {
            $this->permissions[] = $permission;
        }else {
            $this->permissions[$key] = $permission;
        }
    }

    /**
     * Mark the end of life of entity
     * 
     * @param  Inoplate\Account\Domain\Models\Role\Permission   $entity
     * @return void
     */
    public function remove(AccountDomainModels\Permission $entity)
    {
        $id = $entity->id()->value();
        $key = array_search($id, array_column($this->permissions, 'id'));

        if(!is_null($key)) {
            unset($this->permissions[$key]);
        }
    }

    /**
     * Convert to domain model
     * 
     * @param  array $role
     * @return Inoplate\Account\Domain\Models\Permission
     */
    protected function toDomainModel($permission)
    {
        $id = new AccountDomainModels\PermissionId($permission['id']);
        $description = new FoundationDomainModels\Description( array_except($permission, ['id']) );

        return new AccountDomainModels\Permission($id, $description);
    }
}