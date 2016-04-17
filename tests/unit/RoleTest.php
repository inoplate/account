<?php

use Inoplate\Account\Domain\Models as AccountDomainModels;
use Inoplate\Foundation\Domain\Models as FoundationDomainModels;

class RoleTest extends \Codeception\TestCase\Test
{
    use \Codeception\Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var array
     */
    protected $permissions;

    /**
     * @var Inoplate\Account\Domain\Models\User\Role
     */
    protected $role;

    protected function _before()
    {
        $this->specifyConfig()->shallowClone();
        
        $id = new AccountDomainModels\RoleId('123456');
        $name = new FoundationDomainModels\Name('Administrator');
        $description = new FoundationDomainModels\Description([]);

        $this->permissions[] = new AccountDomainModels\Permission(
            new AccountDomainModels\PermissionId('abcde'),
            new FoundationDomainModels\Description([])
        );

        $this->permissions[] = new AccountDomainModels\Permission(
            new AccountDomainModels\PermissionId('fghij'),
            new FoundationDomainModels\Description([])
        );

        $this->permissions[] = new AccountDomainModels\Permission(
            new AccountDomainModels\PermissionId('klmno'),
            new FoundationDomainModels\Description([])
        );

        $this->role = new AccountDomainModels\Role($id, $name, $description, $this->permissions);
    }

    protected function _after()
    {
        unset($this->rules);
        unset($this->permissions);
    }

    public function testRoleDescribe()
    {
        $this->specify("Redecribe user", function(){
            $name = new FoundationDomainModels\Name('administrator');
            $description = new FoundationDomainModels\Description(['name' => 'administrators', 'avatar' => 'avatar.jpg']);

            $this->role->setName($name);
            $this->role->describe($description);

            expect("Role has new name", $this->role->name())->equals($name);
            expect("Role has new description", $this->role->description())->equals($description);
        });
    }

    // tests
    public function testAttachPermission()
    {
        $permission = new AccountDomainModels\Permission(
            new AccountDomainModels\PermissionId('pqrst'),
            new FoundationDomainModels\Description([])
        );

        $this->specify("Attach permission with the existing one", function(){
            $this->role->attachPermission($this->permissions[0]);

            $permissions = $this->role->permissions();
            expect("Role's permissions still same", $permissions)->equals($this->permissions);
        });

        $this->specify("Attach permission with the new one", function() use ($permission){
            $this->role->attachPermission($permission);

            $permissions = $this->role->permissions();
            expect("Now role's permissions has one new", $permissions)->equals(array_merge($this->permissions, [$permission]));
        });
    }

    public function testDetachPermission()
    {
        $permission = new AccountDomainModels\Permission(
            new AccountDomainModels\PermissionId('pqrst'),
            new FoundationDomainModels\Description([])
        );

        $this->specify("Detach permission with none existing one", function() use ($permission){
            $this->role->detachPermission($permission);

            $permissions = $this->role->permissions();
            expect("Role's permissions still same", $permissions)->equals($this->permissions);
        });

        $this->specify("Detach permission with the existing one", function(){
            $this->role->detachPermission($this->permissions[0]);

            $permissions = $this->role->permissions();
            expect("Now role's permissions decreaesed one", count($permissions))->equals(2);
        });
    }
}