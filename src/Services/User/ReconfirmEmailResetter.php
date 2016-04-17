<?php

namespace Inoplate\Account\Services\User;

use Ramsey\Uuid\Uuid;
use Inoplate\Account\App\Services\User\EmailResetter as Contract;
use Inoplate\Account\Repositories\User\EmailReset as EmailResetRepository;
use Inoplate\Account\Events\User\UserEmailWasUpdatedAndWaitForConfirmation;

class ReconfirmEmailResetter implements Contract
{
    /**
     * @var Inoplate\Account\Repositories\User\EmailReset
     */
    protected $emailResetRepository;

    /**
     * Create new EmailResetter instance
     * 
     * @param Inoplate\Account\Repositories\User\EmailReset $emailResetRepository
     */
    public function __construct(EmailResetRepository $emailResetRepository)
    {
        $this->emailResetRepository = $emailResetRepository;
    }

    /**
     * Reset an email
     * 
     * @param  string $userId
     * @param  string $token
     * @return void
     */
    public function reset($userId, $email)
    {
        $this->emailResetRepository->register($userId, $email);

        event(new UserEmailWasUpdatedAndWaitForConfirmation($userId));
    }
}