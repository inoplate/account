<?php

namespace Inoplate\Account\Domain\Commands;

use Inoplate\Foundation\Domain\Command;

class DescribeUser extends Command
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var username
     */
    protected $username;

    /**
     * @var email
     */
    protected $email;

    /**
     * @var array
     */
    protected $description;

    /**
     * Create new DescribeUser instance
     * 
     * @param mixed $id       
     * @param mixed $username 
     * @param mixed $email    
     * @param array $description
     */
    public function __construct($id, $username, $email, $description = [])
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->description = $description;
    }
}