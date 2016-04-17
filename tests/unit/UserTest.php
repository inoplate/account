<?php

use \Codeception\Util\Stub;
use Inoplate\Account\Domain\Models as AccountDomainModels;
use Inoplate\Foundation\Domain\Models as FoundationDomainModels;
use Inoplate\Account\Domain\Exceptions\UserMustHaveAtLeastOneRole;

class UserTest extends \Codeception\TestCase\Test
{
    use \Codeception\Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var array
     */
    protected $roles;

    /**
     * @var  Inoplate\Account\Domain\Models\User
     */
    protected $user;

    protected function _before()
    {
        $this->specifyConfig()->shallowClone();
        
        $idA = new AccountDomainModels\RoleId('123456');
        $nameA = new FoundationDomainModels\Name('Administrator');
        $descriptionA = new FoundationDomainModels\Description([]);

        $roleA = new AccountDomainModels\Role($idA, $nameA, $descriptionA);

        $idB = new AccountDomainModels\RoleId('789010');
        $nameB = new FoundationDomainModels\Name('Contributor');
        $descriptionB = new FoundationDomainModels\Description([]);

        $roleB = new AccountDomainModels\Role($idB, $nameB, $descriptionB);

        $this->roles = [ $roleA, $roleB ];

        $id = new AccountDomainModels\UserId('123456');
        $username = new AccountDomainModels\Username('admin');
        $email = new FoundationDomainModels\Email('admin@admin.com');
        $description = new FoundationDomainModels\Description(['name' => 'administrator', 'avatar' => 'avatar.jpg']);

        $this->user = new AccountDomainModels\User($id, $username, $email, $description, $this->roles);
    }

    protected function _after()
    {
        unset($this->user);
        unset($this->roles);
    }

    public function testUserDescribe()
    {
        $this->specify("Redecribe user", function(){
            $username = new AccountDomainModels\Username('admink');
            $email = new FoundationDomainModels\Email('admink@admin.com');
            $description = new FoundationDomainModels\Description(['name' => 'administrators', 'avatar' => 'avatar.jpg']);

            $this->user->setUsername($username);
            $this->user->setEmail($email);
            $this->user->describe($description);

            expect("User has new username", $this->user->username())->equals($username);
            expect("User has new email", $this->user->email())->equals($email);
            expect("User has new description", $this->user->description())->equals($description);
        });
    }

    public function testGrantRole()
    {

        $id = new AccountDomainModels\RoleId('111213');
        $name = new FoundationDomainModels\Name('Spectator');
        $description = new FoundationDomainModels\Description([]);

        $role = new AccountDomainModels\Role($id, $name, $description);

        $this->specify("Grant role with existing one", function(){
            $this->user->grantRole($this->roles[0]);

            $roles = $this->user->roles();
            expect("User has one roles", count($roles))->equals(2);
            expect("User only has contributor role", $roles)->equals($this->roles);
        });

        $this->specify("Grant role with new one one", function() use ($role){
            $this->user->grantRole($role);

            $roles = $this->user->roles();
            expect("User has one roles", count($roles))->equals(3);
            expect("User only has contributor role", $roles)->equals(array_merge($this->roles, [$role]));
        });   
    }

    public function testRevokeRole()
    {
        $this->specify("Revoke role oke", function(){
            $this->user->revokeRole($this->roles[0]);

            $roles = $this->user->roles();
            expect("User has one roles", count($roles))->equals(1);
            expect("User only has contributor role", $roles)->equals([$this->roles[1]]);
        });

        $this->specify("Revoke role throw exception", function(){
            $this->user->revokeRole($this->roles[0]);
            $this->user->revokeRole($this->roles[1]);
        }, ['throws' => new UserMustHaveAtLeastOneRole]);
    }
}