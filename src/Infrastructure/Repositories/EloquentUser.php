<?php

namespace Inoplate\Account\Infrastructure\Repositories;

use Ramsey\Uuid\Uuid;
use Inoplate\Account\User as Model;
use Roseffendi\Dales\DTDataProvider;
use Roseffendi\Dales\Laravel\ProvideDTData;
use Inoplate\Account\Domain\Models as AccountDomainModels;
use Inoplate\Foundation\Domain\Models as FoundationDomainModels;
use Inoplate\Account\Domain\Repositories\User as Contract;
use Inoplate\Account\Domain\Repositories\Role as RoleRepository;

class EloquentUser implements Contract, DTDataProvider
{
    use ProvideDTData;

    /**
     * @var Inoplate\Account\User
     */
    protected $model;

    /**
     * @var Inoplate\Account\Domain\Repositories\RoleRepository
     */
    protected $roleRepository;

    /**
     * Available columns to serve in datatables
     * 
     * @var array
     */
    protected $dtColumns = ['id', 'username', 'email', 'name', 'active', 'created_at', 'deleted_at'];

    /**
     * Unsearchable columns
     * 
     * @var array
     */
    protected $dtUnsearchable = ['id', 'active', 'created_at', 'deleted_at'];

    /**
     * Unsortable columns
     * 
     * @var array
     */
    protected $dtUnsortable = ['id', 'roles'];

    /**
     * Create new EloquentUser instance
     * 
     * @param Model          $model
     * @param RoleRepository $roleRepository
     */
    public function __construct(Model $model, RoleRepository $roleRepository)
    {
        $this->model = $model;
        $this->roleRepository = $roleRepository;
        $this->dtModel = $model->with('roles');
    }

    /**
     * Retrieve user generated identity
     * 
     * @return Inoplate\Account\Domain\Models\UserId
     */
    public function nextIdentity()
    {
        $id = Uuid::uuid4();

        return new AccountDomainModels\UserId($id->toString());
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
     * Retrieve user by id
     * 
     * @param  mixed $id
     * @return Inoplate\Account\Domain\Models\User
     */
    public function findById($id)
    {
        $item = $this->model->find($id);

        return $this->toDomainModel($item);
    }

    /**
     * Retrieve user by email
     * 
     * @param  mixed $email
     * @return Inoplate\Account\Domain\Models\User
     */
    public function findByEmail($email)
    {   
        $item = $this->model->where('email', $email)->first();

        return $this->toDomainModel($item);
    }

    /**
     * Retrieve user by username
     * 
     * @param  mixed $username
     * @return Inoplate\Account\Domain\Models\User
     */
    public function findByUsername($username)
    {
        $item = $this->model->where('username', $username)->first();

        return $this->toDomainModel($item);
    }

    /**
     * Save entity updates
     * 
     * @param  Inoplate\Account\Domain\Models\User   $entity
     * @return void
     */
    public function save(AccountDomainModels\User $entity)
    {
        $entity = $entity->toArray();

        $user = $this->model->firstORNew(['id' => $entity['id']]);
        $user->username = $entity['username'];
        $user->email = $entity['email'];
        $description = $entity['description'];

        $roles = $entity['roles'];
        $rolesToSync = [];

        foreach ($description as $key => $value) {
            $user->{$key} = $value;
        }

        $user->save();

        foreach ($roles as $role) {
            $rolesToSync[] = $role['id'];
        }

        $user->roles()->sync($rolesToSync);
    }

    /**
     * Mark the end of life of entity
     * 
     * @param  Inoplate\Account\Domain\Models\User   $entity
     * @return void
     */
    public function remove(AccountDomainModels\User $entity)
    {
        $id = $entity->id()->value();

        $this->model->destroy($id);
    }

    /**
     * Scope of trashed datatables
     * 
     * @return self
     */
    public function dtScopeOfTrashed()
    {
        $this->dtModel = $this->dtModel->onlyTrashed();

        return $this;
    }

    /**
     * Scope of user status
     * 
     * @param  boolean $status
     * @return self
     */
    public function dtScopeOfStatus($status)
    {
        $this->dtModel = $this->dtModel->ofStatus($status);

        return $this;
    }

    /**
     * Scope of roles
     * 
     * @param  array|null $roles
     * @return self
     */
    public function dtScopeOfRoles($roles)
    {
        $this->dtModel = $this->dtModel->ofRoles($roles);

        return $this;
    }

    /**
     * Convert to domain model
     * 
     * @param  Model $user
     * @return null|Inoplate\Account\Domain\Models\User
     */
    protected function toDomainModel($user)
    {
        if( is_null($user) ) {
            return $user;
        }else {
            $id = new AccountDomainModels\UserId($user->id);
            $username = new AccountDomainModels\Username($user->username);
            $email = new FoundationDomainModels\Email($user->email);
            $description = new FoundationDomainModels\Description(array_except( $user->toArray(), ['id', 'username', 'email', 'roles'] ));

            $plainRoles = $user->roles;
            $roles = [];

            foreach ($plainRoles as $role) {
                $roles[] = $this->roleRepository->findById($role->id);
            }

            return new AccountDomainModels\User($id, $username, $email, $description, $roles);
        }
    }
}