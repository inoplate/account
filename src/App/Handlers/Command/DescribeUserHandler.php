<?php

namespace Inoplate\Account\App\Handlers\Command;

use Inoplate\Account\Domain\Models as AccountDomainModels;
use Inoplate\Account\Domain\Commands\DescribeUser;
use Inoplate\Account\Domain\Repositories\User as UserRepository;
use Inoplate\Account\Domain\Specifications as AccountDomainSpecifications;
use Inoplate\Foundation\Domain\Models as FoundationDomainModels;
use Inoplate\Foundation\App\Services\Events\Dispatcher as Events;
use Inoplate\Foundation\App\Exceptions\ValueNotFoundException;
use Inoplate\Foundation\App\Exceptions\ValueIsNotUniqueException;

class DescribeUserHandler
{
     /**
     * @var Inoplate\Account\Domain\Repositories\User
     */
    protected $userRepository;

    /**
     * @var Inoplate\Foundation\App\Services\Event\Dispatcher
     */
    protected $events;

    /**
     * Create DescribeUserHandler instance
     * 
     * @param UserRepository $userRepository
     * @param Events         $events
     */
    public function __construct(UserRepository $userRepository, Events $events)
    {
        $this->userRepository = $userRepository;
        $this->events = $events;
    }

    /**
     * Handle user unregistration
     * 
     * @param  DescribeUser $command
     * @return void
     */
    public function handle(DescribeUser $command)
    {
        $id = new AccountDomainModels\UserId($command->id);
        $username = new AccountDomainModels\Username($command->username);
        $email = new FoundationDomainModels\Email($command->email);
        $description = new FoundationDomainModels\Description($command->description);

        $this->ensureUsernameIsUnique($username, $id);
        $this->ensureEmailIsUnique($email, $id);

        $user = $this->retrieveUser($id);

        $user->setUsername($username);
        $user->setEmail($email);
        $user->describe($description);

        $this->userRepository->save($user);

        $this->events->fire($user->releaseEvents());
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
     * Ensure username is unique
     * 
     * @param  AccountDomainModels\Username $username
     * @param  AccountDomainModels\Username $UserId
     * @return void
     */
    protected function ensureUsernameIsUnique(AccountDomainModels\Username $username, AccountDomainModels\UserId $id)
    {
        $specification = new AccountDomainSpecifications\UsernameIsUnique($this->userRepository, $id);

        if(!$specification->isSatisfiedBy($username))
            throw new ValueIsNotUniqueException("Username [(string)$username] was already taken");
            
    }

    /**
     * Ensure email is unique
     * 
     * @param  FoundationDomainModels\Email $email
     * @param  AccountDomainModels\Username $UserId
     * @return void
     */
    protected function ensureEmailIsUnique(FoundationDomainModels\Email $email, AccountDomainModels\UserId $id)
    {
        $specification = new AccountDomainSpecifications\EmailIsUnique($this->userRepository, $id);

        if(!$specification->isSatisfiedBy($email))
            throw new ValueIsNotUniqueException("Email [(string)$email] was already taken");
    }
}