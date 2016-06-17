<?php namespace Inoplate\Account\Widgets;

use Illuminate\Contracts\Auth\Guard;
use Inoplate\Account\Domain\Models as AccountDomainModels;
use Inoplate\Account\Domain\Repositories\User as UserRepository;
use Inoplate\Widget\BaseWidget;

class Usercard extends BaseWidget
{
    /**
     * @var integer
     */
    protected $order = 1;

    /**
     * @var string
     */
    protected $view = 'inoplate-account::widgets.user-card';

    /**
     * @var Inoplate\Account\Domain\Models\User
     */
    protected $user;

    /**
     * Create new Usercard instance
     * 
     * @param Guard          $auth 
     * @param UserRepository $userRepository
     */
    public function __construct(Guard $auth, UserRepository $userRepository)
    {
        $this->user = $userRepository->findById($auth->user()->id);
    }

    /**
     * @inherit_docs
     */
    public function options()
    {
        return [
            'user' => $this->user->toArray()
        ];
    }
}