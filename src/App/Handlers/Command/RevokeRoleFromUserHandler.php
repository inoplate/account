<?php

namespace Inoplate\Account\App\Handlers\Command;

use Inoplate\Account\Domain\Models as AccountDomainModels;
use Inoplate\Account\Domain\Repositories\User as UserRepository;
use Inoplate\Account\Domain\Repositories\Role as RoleRepository;
use Inoplate\Account\Domain\Commands\RevokeRoleFromUser;
use Inoplate\Foundation\App\Services\Events\Dispatcher as Events;

class RevokeRoleFromUserHandler
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
     * Create new RevokeRoleFromUserHadler Intance
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
     * @param  RevokeRoleFromUser $command
     * @return void
     */
    public function handle(RevokeRoleFromUser $command)
    {
        $userId = new AccountDomainModels\UserId($command->id);
        $roleId = new AccountDomainModels\RoleId($command->roleId);

        $user = $this->retrieveUser($userId);
        $role = $this->retrieveRole($roleId);

        $user->revokeRole($role);
        $this->userRepository->save($user);

        $this->events->fire( $user->releaseEvents() );
    }

    /**
     * Retrieve user and ensure user is exist
     * 
     * @param  AccountDomainModels\UserId $id
     * @return AccountDomainModels\User
     */
    protected function retrieveUser(AccountDomainModels\UserId $id)
    {
        $user = $this->userRepository->findById($id);

        if(is_null($user)) 
            throw new ValueNotFoundException("[(string)$id] is not valid user id");

        return $user;
    }

    /**
     * Retrieve role and ensure role is exist
     * 
     * @param  AccountDomainModels\RoleId $id
     * @return AccountDomainModels\Role
     */
    protected function retrieveRole(AccountDomainModels\RoleId $id)
    {
        $role = $this->roleRepository->findById($id);

        if(is_null($role)) 
            throw new ValueNotFoundException("[(string)$id] is not valid role id");

        return $role;
    }
}