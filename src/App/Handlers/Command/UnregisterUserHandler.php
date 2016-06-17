<?php

namespace Inoplate\Account\App\Handlers\Command;

use Inoplate\Account\Domain\Models as AccountDomainModels;
use Inoplate\Account\Domain\Commands\UnregisterUser;
use Inoplate\Account\Domain\Events\UserWasUnregistered;
use Inoplate\Account\Domain\Repositories\User as UserRepository;
use Inoplate\Foundation\App\Services\Events\Dispatcher as Events;
use Inoplate\Foundation\App\Exceptions\ValueNotFoundException;

class UnregisterUserHandler
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
     * Create UnregisterUserHandler instance
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
     * @param  UnregisterUser $command
     * @return void
     */
    public function handle(UnregisterUser $command)
    {
        $id = $command->id;
        $user = $this->retrieveUser($id);

        $this->userRepository->remove($user);

        $this->events->fire([ new UserWasUnregistered($user) ]);
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
}