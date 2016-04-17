<?php

namespace Inoplate\Account\Domain\Models;

use Inoplate\Foundation\Domain\Models as FoundationModels;
use Inoplate\Foundation\Domain\Contracts\Describeable;
use Inoplate\Account\Domain\Events;
use Inoplate\Account\Domain\Exceptions;

class User extends FoundationModels\Entity implements Describeable
{
    use FoundationModels\Describeable;

    /**
     * @var Username
     */
    protected $username;

    /**
     * @var FoundationModels\Email
     */
    protected $email;

    /**
     * @var array
     */
    protected $roles;

    /**
     * Create new user instance
     * 
     * @param UserId                            $id
     * @param Username                          $username
     * @param FoundationModels\Email             $email
     * @param FoundationModels\Description       $description
     * @param array                             $roles
     */
    public function __construct(
        UserId $id, 
        Username $username, 
        FoundationModels\Email $email, 
        FoundationModels\Description $description, 
        $roles
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->roles = $roles;
        $this->description = $description;

        $this->ensureUserHasRole();
    }

    /**
     * Retreive user's username
     * 
     * @return Username
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * Retrieve user's email
     * 
     * @return FoundationModels\Email
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * Retrieve user's roles
     * 
     * @return array
     */
    public function roles()
    {
        return $this->roles;
    }

    /**
     * Set user's username
     * 
     * @param Username $username
     * @return void
     */
    public function setUsername(Username $username)
    {
        if(!$this->username()->equal($username)) {
            $this->username = $username;
            $this->recordEvent(new Events\UsernameWasUpdated($this));
        }
    }

    /**
     * Set user's email
     * 
     * @param FoundationModels\Email $email
     */
    public function setEmail(FoundationModels\Email $email)
    {
        if(!$this->email()->equal($email)) {
            $this->email = $email;
            $this->recordEvent(new Events\EmailWasUpdated($this));
        }
    }

    /**
     * Grant role to user
     * 
     * @param  Role   $role
     * @return void
     */
    public function grantRole(Role $role)
    {
        if(!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }

        $this->recordEvent(new Events\RoleWasGrantedToUser($this, $role));
    }

    /**
     * Revoke role from user
     * 
     * @param  Role   $role
     * @return void
     */
    public function revokeRole(Role $role)
    {
        $this->roles = array_values(array_filter($this->roles, function($search) use ($role){
            return !$search->equal($role);
        }));

        $this->ensureUserHasRole();
        $this->recordEvent(new Events\RoleWasRevokedFromUser($this, $role));
    }

    /**
     * Ensure the user at least has role
     * 
     * @return void
     */
    protected function ensureUserHasRole()
    {
        if(count($this->roles) == 0) {
            throw new Exceptions\UserMustHaveAtLeastOneRole;
        }
    }
}