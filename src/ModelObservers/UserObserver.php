<?php

namespace Inoplate\Account\ModelObservers;

use Inoplate\Account\User;

class UserObserver
{
    public function deleted($model)
    {
        if($model = User::onlyTrashed()->find($model->id)) {
            $model->email = str_replace($model->id.'-', '', $model->email);
            $model->username = str_replace($model->id.'-', '', $model->username);

            $model->email = $model->id.'-'.$model->email;
            $model->username = $model->id.'-'.$model->username;

            $model->save();
        }
    }

    public function restoring($model)
    {
        $email = str_replace($model->id.'-', '', $model->email);
        $username = str_replace($model->id.'-', '', $model->username);

        $existing = User::where(function($query) use ($email, $username){
            $query->where('email', $email);
            $query->orWhere('username', $username);
        })->first();

        if($existing) {
            $model->email = $email != $existing->email ? $email : $model->email;
            $model->username = $username != $existing->username ? $username : $model->username;
        }else {
            $model->email = $email;
            $model->username = $username;
        }

        $model->save();
    }
}