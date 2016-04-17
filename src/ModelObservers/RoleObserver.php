<?php

namespace Inoplate\Account\ModelObservers;

use Inoplate\Account\Role;
use Inoplate\Account\User;
use Inoplate\Foundation\Exceptions\DataComplainException;

class RoleObserver
{
    public function deleting($model)
    {
        if($model->users->count() > 0) {
            throw new DataComplainException( trans('inoplate-account::messages.users.role-complain', ['name' => $model->name]) );
        }
    }

    public function deleted($model)
    {
        if($model = Role::onlyTrashed()->find($model->id)) {
            $model->name = str_replace($model->id.'-', '', $model->name);
            $model->slug = str_replace($model->id.'-', '', $model->slug);

            $model->name = $model->id.'-'.$model->name;
            $model->slug = $model->id.'-'.$model->slug;

            $model->save();
        }
    }

    public function restoring($model)
    {
        $name = str_replace($model->id.'-', '', $model->name);
        $slug = str_replace($model->id.'-', '', $model->slug);

        $existing = Role::where(function($query) use ($name, $slug){
            $query->where('name', $name);
            $query->orWhere('slug', $slug);
        })->first();

        if($existing) {
            $model->name = $name != $existing->name ? $name : $model->name;
            $model->slug = $slug != $existing->slug ? $slug : $model->slug;
        }else {
            $model->name = $name;
            $model->slug = $slug;
        }

        $model->save();
    }
}