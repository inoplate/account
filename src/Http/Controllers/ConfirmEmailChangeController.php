<?php

namespace Inoplate\Account\Http\Controllers;

use Inoplate\Foundation\Http\Controllers\Controller;
use Inoplate\Account\Repositories\User\EmailReset;
use Inoplate\Foundation\App\Services\Bus\Dispatcher;
use Inoplate\Account\Domain\Models\UserId;
use Inoplate\Account\Domain\Commands\DescribeUser;
use Inoplate\Account\Domain\Repositories\User as UserRepository;
use Inoplate\Account\Repositories\User\EmailReset as EmailResetRepository;
use Inoplate\Account\Events\User\NewUserEmailWasConfirmed;

class ConfirmEmailChangeController extends Controller
{
    protected $emailResetRepository;

    protected $userRepository;

    public function __construct(EmailResetRepository $emailResetRepository, UserRepository $userRepository)
    {
        $this->emailResetRepository = $emailResetRepository;
        $this->userRepository = $userRepository;
    }

    public function putConfirm($token, Dispatcher $bus)
    {
        $email = $this->emailResetRepository->findByToken($token);

        if(is_null($email))
            abort(404);

        $user = $this->userRepository->findById($email->user_id);
        $userId = $user->id()->value();

        $bus->dispatch( new DescribeUser($userId, $user->username()->value(), $email->email));

        $this->emailResetRepository->remove($userId);

        event(new NewUserEmailWasConfirmed($userId));

        return redirect('/')->with('message', trans('account::message.profile.email_changed'));
    }
}