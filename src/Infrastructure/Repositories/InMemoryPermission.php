<?php

namespace Inoplate\Account\Infrastructure\Repositories;

use Inoplate\Account\Domain\Models as AccountDomainModels;
use Inoplate\Foundation\Domain\Models as FoundationDomainModels;
use Inoplate\Account\Domain\Repositories\Permission as Contract;
use Inoplate\Auth\Contracts\Permission\Repository as Permission;

class InMemoryPermission implements Contract
{
    /**
     * @var Inoplate\Auth\Contracts\Permission\Repository
     */
    protected $permission;

    /**
     * Create new InMemoryPermission instance
     * 
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    /**
     * Retrieve all permissions
     * 
     * @return array
     */
    public function all()
    {
        $return = [];
        $permissions = $this->permission->all();

        foreach ($permissions as $permission) {
            $return[] = $this->toDomainModel($permission);
        }

        return $return;
    }

    /**
     * Retrieve permission by id
     * 
     * @param  mixed $id
     * @return null|Inoplate\Account\Domain\Models\Permission
     */
    public function findById($id)
    {
        $permission = $this->permission->get($id);

        return $this->toDomainModel($permission);
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
        if(is_null($permission)) {
            return $permission;
        }

        $id = new AccountDomainModels\PermissionId($permission['name']);
        $description = new FoundationDomainModels\Description( array_except($permission, 'name') );

        return new AccountDomainModels\Permission($id, $description);
    }
}