<?php

namespace Inoplate\Account\App\Handlers\Command;

use Inoplate\Account\Domain\Commands\DetachPermissionFromRole;
use Inoplate\Account\Domain\Repositories\Role as RoleRepository;
use Inoplate\Account\Domain\Repositories\Permission as PermissionRepository;
use Inoplate\Foundation\App\Services\Events\Dispatcher as Events;
use Inoplate\Foundation\App\Exceptions\ValueNotFoundException;

class DetachPermissionFromRoleHandler
{
    /**
     * @var Inoplate\Account\Domain\Repositories\Role
     */
    protected $roleRepository;

    /**
     * @var Inoplate\Account\Domain\Repositories\Permission
     */
    protected $permissionRepository;

    /**
     * @var Inoplate\Foundation\App\Services\Event\Dispatcher
     */
    protected $events;

    /**
     * Create DetachPermissionFromRole instance
     * 
     * @param RoleRepository        $roleRepository
     * @param PermissionRepository  $permissionRepository
     * @param Events                $events
     */
    public function __construct(
        RoleRepository $roleRepository, 
        PermissionRepository $permissionRepository, 
        Events $events
    ) {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
        $this->events = $events;
    }

    /**
     * Handle detach permission to role
     * 
     * @param  DetachPermissionFromRole $command
     * @return void
     */
    public function handle(DetachPermissionFromRole $command)
    {
        $id = $command->id;
        $permissionId = $command->permissionId;

        $role = $this->retrieveRole($id);
        $permission = $this->retrievePermission($permissionId);

        $role->detachPermission($permission);
        $this->roleRepository->save($role);

        $this->events->fire( $role->releaseEvents() );
    }

    /**
     * Retrieve role and ensure role is exist
     * 
     * @param  mixed $id
     * @return AccountDomainModels\Role
     */
    protected function retrieveRole($id)
    {
        $role = $this->roleRepository->findById($id);

        if(is_null($role)) 
            throw new ValueNotFoundException("[$id] is not valid role id");

        return $role;
    }

    /**
     * Retrieve role and ensure role is exist
     * 
     * @param  mixed $id
     * @return AccountDomainModels\Permission
     */
    protected function retrievePermission($id)
    {
        $role = $this->permissionRepository->findById($id);

        if(is_null($role)) 
            throw new ValueNotFoundException("[$id] is not valid permission id");

        return $role;
    }
}