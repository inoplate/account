<?php

namespace Inoplate\Account\Domain\Models;

use Inoplate\Foundation\Domain\Models as FoundationModels;
use Inoplate\Foundation\Domain\Contracts\Describeable;
use Inoplate\Account\Domain\Events;

class Role extends FoundationModels\Entity implements Describeable
{
    use FoundationModels\Describeable;
    
    /**
     * @var FondationModel\Name
     */
    protected $name;

    /**
     * @var array
     */
    protected $permissions;

    /**
     * Create new Role instance
     * 
     * @param RoleId                        $id
     * @param FoundationModels\Name         $name
     * @param array                         $permissions
     * @param FoundationModels\Description  $description
     */
    public function __construct(
        RoleId $id, 
        FoundationModels\Name $name, 
        FoundationModels\Description $description, 
        $permissions = []
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->permissions = $permissions;
        $this->description = $description;
    }

    /**
     * Retrieve role name
     * 
     * @return FoundationModels\Name
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Return role's permission
     * 
     * @return array
     */
    public function permissions()
    {
        return $this->permissions;
    }

    /**
     * Set role's name
     * 
     * @param FoundationModels\Name $name
     */
    public function setName(FoundationModels\Name $name)
    {
        $this->name = $name;
    }

    /**
     * Attach permission to role
     * 
     * @param  Permission $permission
     * @return void
     */
    public function attachPermission(Permission $permission)
    {
        if(!in_array($permission, $this->permissions)) {
            $this->permissions[] = $permission;
        }

        $this->recordEvent(new Events\PermissionWasAttachedToRole($this, $permission));
    }

    /**
     * Detach permission from role
     * 
     * @param  Permission $permission
     * @return void
     */
    public function detachPermission(Permission $permission)
    {
        $this->permissions = array_values(array_filter($this->permissions, function($search) use ($permission){
            return !$search->equal($permission);
        }));

        $this->recordEvent(new Events\PermissionWasDetachedFromRole($this, $permission));
    }
}