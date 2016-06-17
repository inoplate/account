<?php

namespace Inoplate\Account\Infrastructure\Repositories;

use Ramsey\Uuid\Uuid;
use Inoplate\Account\Repositories\User\EmailReset as Contract;
use Inoplate\Account\EmailReset as Model;

class EloquentEmailReset implements Contract
{

    /**
     * @var Inoplate\Account\EmailReset
     */
    protected $model;

    /**
     * Create new EloquentEmailReset instance
     * 
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Retrieve email reset by user id
     * 
     * @param  string $userId
     * @return Model
     */
    public function findByUserId($userId)
    {
        return $this->model->where('user_id', $userId)->first();
    }

    /**
     * Retrieve email reset by email
     * 
     * @param  string $email
     * @return Model
     */
    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Retrieve email reset by token
     * 
     * @param  string $token
     * @return Model
     */
    public function findByToken($token)
    {
        return $this->model->where('token', $token)->first();
    }

    /**
     * Register new email change
     * 
     * @param  string $userId
     * @param  string $email
     * @return void
     */
    public function register($userId, $email)
    {
        $emailReset = $this->model->firstOrNew([ 'user_id' => $userId ]);

        $emailReset->email = $email;
        $emailReset->token = Uuid::uuid4();

        $emailReset->save();
    }

    /**
     * Remove registered email change
     * 
     * @param  string $userId
     * @return void
     */
    public function remove($userId)
    {
        $this->model->where('user_id', $userId)->delete();
    }
}