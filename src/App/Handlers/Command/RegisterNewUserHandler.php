<?php

namespace Inoplate\Account\App\Handlers\Command;

use Inoplate\Account\Domain\Models as AccountDomainModels;
use Inoplate\Account\Domain\Specifications as AccountDomainSpecifications;
use Inoplate\Account\Domain\Repositories\User as UserRepository;
use Inoplate\Account\Domain\Repositories\Role as RoleRepository;
use Inoplate\Account\Domain\Events\UserWasRegistered;
use Inoplate\Account\Domain\Commands\RegisterNewUser;
use Inoplate\Foundation\Domain\Models as FoundationDomainModels;
use Inoplate\Foundation\App\Services\Events\Dispatcher as Events;
use Inoplate\Foundation\App\Exceptions\ValueIsNotUniqueException;

class RegisterNewUserHandler
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
     * Create new RegisterNewUserHandler Intance
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
     * @param  RegisterNewUser $command
     * @return void
     */
    public function handle(RegisterNewUser $command)
    {
        $username = new AccountDomainModels\Username($command->username);
        $email = new FoundationDomainModels\Email($command->email);
        $description = new FoundationDomainModels\Description($command->description);
        $id = $this->userRepository->nextIdentity();
        $roles = count($command->roles) ? $this->buildRoles($command->roles) : $this->roleRepository->getDefaultRoles();

        $this->ensureUsernameIsUnique($username);
        $this->ensureEmailIsUnique($email);

        $user = new AccountDomainModels\User($id, $username, $email, $description, $roles);

        $this->userRepository->save($user);
        $this->events->fire([ new UserWasRegistered($user) ]);
    }

    /**
     * Build roles from id of roles
     * 
     * @param  array $idOfRoles
     * @return array
     */
    protected function buildRoles($idOfRoles)
    {
        $roles = [];

        foreach ($idOfRoles as $id) {
            $roles[] = $this->roleRepository->findById(new AccountDomainModels\RoleId($id));
        }

        return $roles;
    }

    /**
     * Ensure username is unique
     * 
     * @param  AccountDomainModels\Username $username
     * @return void
     */
    protected function ensureUsernameIsUnique(AccountDomainModels\Username $username)
    {
        $specification = new AccountDomainSpecifications\UsernameIsUnique($this->userRepository);

        if(!$specification->isSatisfiedBy($username))
            throw new ValueIsNotUniqueException("Username [$username] was already taken");
            
    }

    /**
     * Ensure email is unique
     * 
     * @param  FoundationModels\Email $email
     * @return void
     */
    protected function ensureEmailIsUnique(FoundationDomainModels\Email $email)
    {
        $specification = new AccountDomainSpecifications\EmailIsUnique($this->userRepository);

        if(!$specification->isSatisfiedBy($email))
            throw new ValueIsNotUniqueException("Email [$email] was already taken");
    }
}
