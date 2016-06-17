<?php

namespace Inoplate\Account\App\Handlers\Command;

use Inoplate\Account\Domain\Models as AccountDomainModels;
use Inoplate\Account\Domain\Commands\DescribeRole;
use Inoplate\Account\Domain\Repositories\Role as RoleRepository;
use Inoplate\Account\Domain\Specifications as AccountDomainSpecifications;
use Inoplate\Foundation\Domain\Models as FoundationDomainModels;
use Inoplate\Foundation\App\Services\Events\Dispatcher as Events;
use Inoplate\Foundation\App\Exceptions\ValueNotFoundException;
use Inoplate\Foundation\App\Exceptions\ValueIsNotUniqueException;

class DescribeRoleHandler
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
     * Handle describe role
     * 
     * @param  DescribeRole $command
     * @return void
     */
    public function handle(DescribeRole $command)
    {
        $name = new FoundationDomainModels\Name($command->name);
        $description = new FoundationDomainModels\Description($command->description);
        $id = new AccountDomainModels\RoleId($command->id);
        $this->ensureNameIsUnique($name, $id);

        $role = $this->retrieveRole($id->value());

        $role->setName($name);
        $role->describe($description);

        $this->roleRepository->save($role);

        $this->events->fire($role->releaseEvents());
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
     * Ensure role name is unique
     * 
     * @param  FoundationDomainModels\Name $name
     * @param  AccountDomainModels\RoleId  $id
     * @return void
     */
    protected function ensureNameIsUnique(FoundationDomainModels\Name $name, AccountDomainModels\RoleId $id)
    {
        $specification = new AccountDomainSpecifications\RolenameIsUnique($this->roleRepository, $id);

        if(!$specification->isSatisfiedBy($name))
            throw new ValueIsNotUniqueException("Role [$name] is exist, choose another name");
    }
}