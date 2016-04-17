<?php

namespace Inoplate\Account\Domain\Commands;

use Inoplate\Foundation\Domain\Command;

class RegisterNewUser extends Command
{
    /**
     * @var mixed
     */
    protected $username;

    /**
     * @var mixed
     */
    protected $email;

    /**
     * @var array
     */
    protected $description;

    /**
     * @var array
     */
    protected $roles;

    /**
     * Create new RegisterNewUser instance
     * 
     * @param mixed $username       
     * @param mixed $email
     * @param array $roles
     * @param array $description
     */
    public function __construct($username, $email, $roles, $description = [])
    {
        $this->username = $username;
        $this->email = $email;
        $this->roles = $roles;
        $this->description = $description;
    }
}