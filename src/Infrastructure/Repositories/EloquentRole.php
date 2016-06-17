<?php

namespace Inoplate\Account\Infrastructure\Repositories;

use Ramsey\Uuid\Uuid;
use Inoplate\Account\Role as Model;
use Inoplate\Account\Domain\Models as AccountDomainModels;
use Inoplate\Foundation\Domain\Models as FoundationDomainModels;
use Inoplate\Account\Domain\Repositories\Role as Contract;
use Inoplate\Account\Domain\Repositories\Permission as PermissionRepository;
use Roseffendi\Dales\DTDataProvider;
use Roseffendi\Dales\Laravel\ProvideDTData;
use DB;

class EloquentRole implements Contract, DTDataProvider
{
    use ProvideDTData;

    /**
     * @var Inoplate\Account\Role
     */
    protected $model;

    /**
     * @var Inoplate\Account\Domain\Repositories\Permission
     */
    protected $permissionRepository;

    /**
     * Available columns to serve in datatables
     * 
     * @var array
     */
    protected $dtColumns = ['id', 'name', 'slug', 'created_at', 'deleted_at'];

    /**
     * Unsearchable columns
     * 
     * @var array
     */
    protected $dtUnsearchable = ['id', 'created_at', 'deleted_at'];

    /**
     * Unsortable columns
     * 
     * @var array
     */
    protected $dtUnsortable = ['id'];

    /**
     * Create new EloquentRole instance
     * 
     * @param Model                 $model
     * @param PermissionRepository  $permissionRepository
     */
    public function __construct(Model $model, PermissionRepository $permissionRepository)
    {
        $this->model = $model;
        $this->permissionRepository = $permissionRepository;
        $this->dtModel = $model;
    }

    /**
     * Retrieve role generated identity
     * 
     * @return Inoplate\Account\Domain\Models\RoleId
     */
    public function nextIdentity()
    {
        $id = Uuid::uuid4();

        return new AccountDomainModels\RoleId($id->toString());
    }

    /**
     * Create new eloquent model
     * 
     * @return Model
     */
    public function getModel()
    {
        return $this->model->newInstance();
    }

    /**
     * Retrieve all role
     * 
     * @return array
     */
    public function all()
    {
        $return = [];
        $roles = $this->model->all();

        foreach ($roles as $role) {
            $return[] = $this->toDomainModel($role);
        }

        return $return;
    }

    /**
     * Retrieve role by id
     * 
     * @param  mixed $id
     * @return Inoplate\Account\Domain\Models\Role
     */
    public function findById($id)
    {
        $role = $this->model->find($id);

        return $this->toDomainModel($role);
    }

    /**
     * Retrieve role by name
     * 
     * @param  mixed $name
     * @return Inoplate\Account\Domain\Models\Role
     */
    public function findByName($name)
    {
        $role = $this->model->where('name', $name)->first();

        return $this->toDomainModel($role);
    }

    /**
     * Retrieve default roles
     * 
     * @return array
     */
    public function getDefaultRoles()
    {
        $role = $this->model->where('is_default', true)->first();

        if(is_null($role)) {
            return [];
        } else {
            return [$this->toDomainModel($role)];
        }
    }

    /**
     * Save entity updates
     * 
     * @param  Inoplate\Account\Domain\Models\Role   $entity
     * @return void
     */
    public function save(AccountDomainModels\Role $entity)
    {
        $entity = $entity->toArray();

        $role = $this->model->firstORNew(['id' => $entity['id']]);
        $role->name = $entity['name'];

        $description = $entity['description'];
        $permissions = $entity['permissions'];
        $permissionsToSync = [];

        foreach ($description as $key => $value) {
            $role->{$key} = $value;
        }

        $role->save();

        foreach ($permissions as $permission) {
            if(!is_null($permission)) {
                $permissionsToSync[] = $permission['id'];
            }
        }

        $this->syncPermission($role->id, $permissionsToSync);
    }

    /**
     * Mark the end of life of entity
     * 
     * @param  Inoplate\Account\Domain\Models\Role   $entity
     * @return void
     */
    public function remove(AccountDomainModels\Role $entity)
    {
        $id = $entity->id()->value();

        $this->model->destroy($id);
    }

    /**
     * Sync permission to role
     * 
     * @param  string $roleId      
     * @param  array  $permissions 
     * @return void
     */
    protected function syncPermission($roleId, array $permissions)
    {
        $data = [];

        DB::table('permission_role')->where('role_id', $roleId)->delete();

        foreach ($permissions as $permission) {
            $data[] = [ 'role_id' => $roleId, 'permission_id' => $permission ];
        }

        if( count($data) ) {
            DB::table('permission_role')->insert($data);
        }
    }

    /**
     * Scope of deleted datatables
     * 
     * @return self
     */
    public function dtScopeOfTrashed()
    {
        $this->dtModel = $this->dtModel->onlyTrashed();

        return $this;
    }

    /**
     * Convert to domain model
     * 
     * @param  Model $role
     * @return null|Inoplate\Account\Domain\Models\Role
     */
    protected function toDomainModel($role)
    {
        if( is_null($role) ) {
            return $role;
        }else {
            $id = new AccountDomainModels\RoleId($role->id);
            $name = new FoundationDomainModels\Name($role->name);
            $description = new FoundationDomainModels\Description(array_except($role->toArray(), ['id', 'name', 'permissions']));

            $matrices = $role->permissions();
            $permissions = [];

            foreach ($matrices as $matrix) {
                $permissions[] = $this->permissionRepository->findById($matrix->permission_id);
            }

            return new AccountDomainModels\Role($id, $name, $description, $permissions);
        }
    }
}