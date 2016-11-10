<?php

namespace Inoplate\Account\Listeners\User;

use Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Inoplate\Account\Events\User\UserEmailWasUpdatedAndWaitForConfirmation;
use Inoplate\Account\EmailReset;
use Inoplate\Account\User as UserModel;

class EmailUserForEmailConfirmation implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(UserEmailWasUpdatedAndWaitForConfirmation $event)
    {
        $userId = $event->userId;
        $user = UserModel::find($userId);
        $reset = EmailReset::where('user_id', $userId)->first();

        if(($reset)&&($user)) {
            Mail::send('inoplate-account::email.reset-email', ['user' => $user, 'reset' => $reset], function($mail) use ($user, $reset){

                 $mail->to($reset->email, $user->name)->subject('Account email change confirmation!');
            });
        }
    }
}