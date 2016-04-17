<?php

namespace Inoplate\Account\App\Handlers\Command;

use Inoplate\Account\Domain\Repositories\Role as RoleRepository;
use Inoplate\Account\Domain\Specifications as AccountDomainSpecifications;
use Inoplate\Account\Domain\Events\RoleWasCreated;
use Inoplate\Account\Domain\Commands\CreateNewRole;
use Inoplate\Account\Domain\Models as AccountDomainModels;
use Inoplate\Foundation\Domain\Models as FoundationDomainModels;
use Inoplate\Foundation\App\Services\Events\Dispatcher as Events;
use Inoplate\Foundation\App\Exceptions\ValueIsNotUniqueException;

class CreateNewRoleHandler
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
     * Create new CreateNewRoleHandler Intance
     * 
     * @param RoleRepository   $roleRepository
     * @param Events           $events
     */
    public function __construct(RoleRepository $roleRepository, Events $events) 
    {
        $this->roleRepository = $roleRepository;
        $this->events = $events;
    }

    /**
     * Handle role creation
     * 
     * @param  CreateNewRole $command
     * @return void
     */
    public function handle(CreateNewRole $command)
    {
        $name = new FoundationDomainModels\Name($command->name);
        $description = new FoundationDomainModels\Description($command->description);
        $id = $this->roleRepository->nextIdentity();

        $this->ensureNameIsUnique($name);

        $role = new AccountDomainModels\Role($id, $name, $description);

        $this->roleRepository->save($role);
        $this->events->fire([ new RoleWasCreated($role) ]);
    }

    /**
     * Ensure role name is unique
     * 
     * @param  FoundationDomainModels\Name $name
     * @return void
     */
    protected function ensureNameIsUnique(FoundationDomainModels\Name $name)
    {
        $specification = new AccountDomainSpecifications\RolenameIsUnique($this->roleRepository);

        if(!$specification->isSatisfiedBy($name))
            throw new ValueIsNotUniqueException("Role [$name] is exist, choose another name");
    }

}