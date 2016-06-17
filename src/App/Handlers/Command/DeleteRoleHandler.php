<?php

namespace Inoplate\Account\App\Handlers\Command;

use Inoplate\Account\Domain\Commands\DeleteRole;
use Inoplate\Account\Domain\Events\RoleWasDeleted;
use Inoplate\Account\Domain\Repositories\Role as RoleRepository;
use Inoplate\Foundation\App\Services\Events\Dispatcher as Events;
use Inoplate\Foundation\App\Exceptions\ValueNotFoundException;

class DeleteRoleHandler
{
     /**
     * @var Inoplate\Account\Domain\Repositories\Role
     */
    protected $roleRepository;

    /**
     * @var Inoplate\Foundation\App\Services\Event\Dispatcher
     */
    protected $events;

    /**
     * Create DeleteRoleHandler instance
     * 
     * @param RoleRepository $roleRepository
     * @param Events         $events
     */
    public function __construct(RoleRepository $roleRepository, Events $events)
    {
        $this->roleRepository = $roleRepository;
        $this->events = $events;
    }

    /**
     * Handle role deletion
     * 
     * @param  DeleteRole $command
     * @return void
     */
    public function handle(DeleteRole $command)
    {
        $id = $command->id;
        $role = $this->retrieveRole($id);

        $this->roleRepository->remove($role);

        $this->events->fire([ new RoleWasDeleted($role) ]);
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

        if(is_null($role)) {
            $id = $role->id()->value();
            throw new ValueNotFoundException("[$id] is not valid role id");
        }

        return $role;
    }
}