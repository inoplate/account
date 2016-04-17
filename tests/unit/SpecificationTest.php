<?php

use Inoplate\Account\Domain\Models as AccountDomainModels;
use Inoplate\Account\Domain\Specifications as AccountDomainSpecifications;
use Inoplate\Foundation\Domain\Models as FoundationDomainModels;
use \Codeception\Util\Stub;

class SpecificationTest extends \Codeception\TestCase\Test
{
    use \Codeception\Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->specifyConfig()->shallowClone();
    }

    protected function _after()
    {
    }

    // tests
    public function testEmailIsUniqueSpecification()
    {
        $user = $this->prepareUser();

        $this->specify("Set email for new user with existing email", function() use ($user) {
            $userRepository = Stub::makeEmpty('Inoplate\Account\Domain\Repositories\User', [
                'findByEmail' => Stub::atLeastOnce(function() use ($user){
                    return $user;
                })
            ], $this);

            $specification = new AccountDomainSpecifications\EmailIsUnique($userRepository);

            expect("Specification is not satisfied", $specification->isSatisfiedBy($user->email()))->false();
        });

        $this->specify("Set email for existing user with another existing user's email", function() use ($user) {
            $userRepository = Stub::makeEmpty('Inoplate\Account\Domain\Repositories\User', [
                'findByEmail' => Stub::atLeastOnce(function() use ($user){
                    return $user;
                })
            ], $this);

            $specification = new AccountDomainSpecifications\EmailIsUnique($userRepository, new AccountDomainModels\UserId('56678'));

            expect("Specification is not satisfied", $specification->isSatisfiedBy($user->email()))->false();
        });

        $this->specify("Set email for existing user with same email", function() use ($user) {
            $userRepository = Stub::makeEmpty('Inoplate\Account\Domain\Repositories\User', [
                'findByEmail' => Stub::atLeastOnce(function() use ($user){
                    return $user;
                })
            ], $this);

            $specification = new AccountDomainSpecifications\EmailIsUnique($userRepository, new AccountDomainModels\UserId('123456'));

            expect("Specification is satisfied", $specification->isSatisfiedBy($user->email()))->true();
        });

        $this->specify("Set email for new user with new email", function() use ($user) {
            $userRepository = Stub::makeEmpty('Inoplate\Account\Domain\Repositories\User', [
                'findByEmail' => Stub::atLeastOnce(function() use ($user){
                    return null;
                })
            ], $this);

            $specification = new AccountDomainSpecifications\EmailIsUnique($userRepository);

            expect("Specification is satisfied", $specification->isSatisfiedBy($user->email()))->true();
        });
    }

    public function testUsernameIsUniqueSpecification()
    {
        $user = $this->prepareUser();

        $this->specify("Set username for new user with existing username", function() use ($user) {
            $userRepository = Stub::makeEmpty('Inoplate\Account\Domain\Repositories\User', [
                'findByUsername' => Stub::atLeastOnce(function() use ($user){
                    return $user;
                })
            ], $this);

            $specification = new AccountDomainSpecifications\UsernameIsUnique($userRepository);

            expect("Specification is not satisfied", $specification->isSatisfiedBy($user->username()))->false();
        });

        $this->specify("Set username for existing user with another existing user's username", function() use ($user) {
            $userRepository = Stub::makeEmpty('Inoplate\Account\Domain\Repositories\User', [
                'findByUsername' => Stub::atLeastOnce(function() use ($user){
                    return $user;
                })
            ], $this);

            $specification = new AccountDomainSpecifications\UsernameIsUnique($userRepository, new AccountDomainModels\UserId('56678'));

            expect("Specification is not satisfied", $specification->isSatisfiedBy($user->username()))->false();
        });

        $this->specify("Set username for existing user with same username", function() use ($user) {
            $userRepository = Stub::makeEmpty('Inoplate\Account\Domain\Repositories\User', [
                'findByUsername' => Stub::atLeastOnce(function() use ($user){
                    return $user;
                })
            ], $this);

            $specification = new AccountDomainSpecifications\UsernameIsUnique($userRepository, new AccountDomainModels\UserId('123456'));

            expect("Specification is satisfied", $specification->isSatisfiedBy($user->username()))->true();
        });

        $this->specify("Set username for new user with new username", function() use ($user) {
            $userRepository = Stub::makeEmpty('Inoplate\Account\Domain\Repositories\User', [
                'findByUsername' => Stub::atLeastOnce(function() use ($user){
                    return null;
                })
            ], $this);

            $specification = new AccountDomainSpecifications\UsernameIsUnique($userRepository);

            expect("Specification is satisfied", $specification->isSatisfiedBy($user->username()))->true();
        });
    }

    public function testRolenameIsUniqueSpecification()
    {
        $role = $this->prepareRole();   

        $this->specify("Set rolename for new role with existing rolename", function() use ($role) {
            $roleRepository = Stub::makeEmpty('Inoplate\Account\Domain\Repositories\Role', [
                'findByName' => Stub::atLeastOnce(function() use ($role){
                    return $role;
                })
            ], $this);

            $specification = new AccountDomainSpecifications\RolenameIsUnique($roleRepository);

            expect("Specification is not satisfied", $specification->isSatisfiedBy($role->name()))->false();
        });

        $this->specify("Set rolename for existing role with another existing role's name", function() use ($role) {
            $roleRepository = Stub::makeEmpty('Inoplate\Account\Domain\Repositories\Role', [
                'findByName' => Stub::atLeastOnce(function() use ($role){
                    return $role;
                })
            ], $this);

            $specification = new AccountDomainSpecifications\RolenameIsUnique($roleRepository, new AccountDomainModels\RoleId('56678'));

            expect("Specification is not satisfied", $specification->isSatisfiedBy($role->name()))->false();
        });

        $this->specify("Set rolename for existing role with same rolename", function() use ($role) {
            $roleRepository = Stub::makeEmpty('Inoplate\Account\Domain\Repositories\Role', [
                'findByName' => Stub::atLeastOnce(function() use ($role){
                    return $role;
                })
            ], $this);

            $specification = new AccountDomainSpecifications\RolenameIsUnique($roleRepository, new AccountDomainModels\RoleId('789010'));

            expect("Specification is satisfied", $specification->isSatisfiedBy($role->name()))->true();
        });

        $this->specify("Set rolename for existing role with same rolename", function() use ($role) {
            $roleRepository = Stub::makeEmpty('Inoplate\Account\Domain\Repositories\Role', [
                'findByName' => Stub::atLeastOnce(function() use ($role){
                    return null;
                })
            ], $this);

            $specification = new AccountDomainSpecifications\RolenameIsUnique($roleRepository);

            expect("Specification is satisfied", $specification->isSatisfiedBy($role->name()))->true();
        });
    }

    protected function prepareRole()
    {
        $id = new AccountDomainModels\RoleId('789010');
        $name = new FoundationDomainModels\Name('Contributor');
        $description = new FoundationDomainModels\Description([]);

        $role = new AccountDomainModels\Role($id, $name, $description);

        return $role;
    }

    protected function prepareUser()
    {
        $id = new AccountDomainModels\RoleId('789010');
        $name = new FoundationDomainModels\Name('Contributor');
        $description = new FoundationDomainModels\Description([]);

        $role = new AccountDomainModels\Role($id, $name, $description);

        $id = new AccountDomainModels\UserId('123456');
        $username = new AccountDomainModels\Username('admin');
        $email = new FoundationDomainModels\Email('admin@admin.com');
        $description = new FoundationDomainModels\Description(['name' => 'administrator', 'avatar' => 'avatar.jpg']);

        $user = new AccountDomainModels\User($id, $username, $email, $description, [$role]);

        return $user;
    }
}