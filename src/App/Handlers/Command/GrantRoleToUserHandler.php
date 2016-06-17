<?php

namespace Inoplate\Account\App\Handlers\Command;

use Inoplate\Account\Domain\Models as AccountDomainModels;
use Inoplate\Account\Domain\Repositories\User as UserRepository;
use Inoplate\Account\Domain\Repositories\Role as RoleRepository;
use Inoplate\Account\Domain\Commands\GrantRoleToUser;
use Inoplate\Foundation\App\Services\Events\Dispatcher as Events;

class GrantRoleToUserHandler
{
    /**
     * @var Inoplate\Account\Domain\Repositories\User
     */
    protected $userRepository;

    /**
     * @var Inoplate\Account\Domain\Repositories\Role
     */
    protected $roleRepository;

    /**
     * @var Inoplate\Foundation\App\Services\Event\Dispatcher
     */
    protected $events;

    /**
     * Create new GrantRoleToUserHadler Intance
     * 
     * @param UserRepository   $userRepository
     * @param RoleRepository   $roleRepository
     * @param Events           $events
     */
    public function __construct(
        UserRepository $userRepository, 
        RoleRepository $roleRepository,
        Events $events
    ) {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->events = $events;
    }

    /**
     * Handle new user registration
     * 
     * @param  GrantRoleToUser $command
     * @return void
     */
    public function handle(GrantRoleToUser $command)
    {
        $userId = $command->id;
        $roleId = $command->roleId;

        $user = $this->retrieveUser($userId);
        $role = $this->retrieveRole($roleId);

        $user->grantRole($role);
        $this->userRepository->save($user);

        $this->events->fire( $user->releaseEvents() );
    }

    /**
     * Retrieve user and ensure user is exist
     * 
     * @param  mixed $id
     * @return AccountDomainModels\User
     */
    protected function retrieveUser($id)
    {
        $user = $this->userRepository->findById($id);

        if(is_null($user)) 
            throw new ValueNotFoundException("[$id] is not valid user id");

        return $user;
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
}