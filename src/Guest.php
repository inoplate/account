<?php

namespace Inologi\Account;

use Roseffendi\Authis\User;

class Guest implements User
{
    public function id()
    {
        return null;
    }

    /**
     * Retrieve user abilities
     * 
     * @return array
     */
    public function abilities()
    {
        return [];
    }
}