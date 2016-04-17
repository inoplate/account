<?php

namespace Inoplate\Account\Infrastructure\Repositories;

use Ramsey\Uuid\Uuid;
use Inoplate\Account\User as Model;
use Inoplate\Account\Domain\Models as AccountDomainModels;
use Inoplate\Foundation\Domain\Models as FoundationDomainModels;
use Inoplate\Account\Domain\Repositories\User as Contract;
use Inoplate\Account\Domain\Repositories\Role as RoleRepository;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Roseffendi\Dales\DTDataProvider;
use Roseffendi\Dales\Laravel\ProvideDTData;

class EloquentUser implements Contract, UserProvider, DTDataProvider
{
    use ProvideDTData;

    /**
     * @var Inoplate\Account\User
     */
    protected $model;

    /**
     * @var Illuminate\Contracts\Hashing\Hasher
     */
    protected $hasher;

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
     * @param HasherContract $hasher
     * @param RoleRepository $roleRepository
     */
    public function __construct(
        Model $model, 
        HasherContract $hasher, 
        RoleRepository $roleRepository,
        Request $request
    ){
        $this->model = $model;
        $this->hasher = $hasher;
        $this->roleRepository = $roleRepository;
        $this->dtModel = $model->with('roles')
                               ->ofStatus($request->input('active'))
                               ->ofRoles($request->input('roles'));
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
     * Retrieve user by id
     * 
     * @param  Inoplate\Account\Domain\Models\UserId $id
     * @return Inoplate\Account\Domain\Models\User
     */
    public function findById(AccountDomainModels\UserId $id)
    {
        $user = $this->model->find($id->value());

        return $this->toDomainModel($user);
    }

    /**
     * Retrieve user by email
     * 
     * @param  Inoplate\Foundation\Domain\Models\Email $email
     * @return Inoplate\Account\Domain\Models\User
     */
    public function findByEmail(FoundationDomainModels\Email $email)
    {
        $user = $this->model->where('email', $email->value())->first();

        return $this->toDomainModel($user);
    }

    /**
     * Retrieve user by username
     * 
     * @param  string $username
     * @return Inoplate\Account\Domain\Models\User
     */
    public function findByUsername(AccountDomainModels\Username $username)
    {
        $user = $this->model->where('username', $username->value())->first();

        return $this->toDomainModel($user);
    }

    /**
     * Save entity updates
     * 
     * @param  Inoplate\Account\Domain\Models\User   $entity
     * @return void
     */
    public function save(AccountDomainModels\User $entity)
    {
        $user = $this->model->firstOrNew([ 'id' => $entity->id()->value() ]);
        $user->id = $entity->id()->value();
        $user->username = $entity->username()->value();
        $user->email = $entity->email()->value();

        $description = $entity->description()->value();
        $domainRoles = $entity->roles();

        $roles = [];

        foreach ($description as $key => $value) {
            $user->{$key} = $value;
        }
        
        $user->save();
        
        foreach ($domainRoles as $role) {
            $roles[] = $role->id()->value();
        }

        $user->roles()->sync($roles);
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
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        return $this->createModel()->newQuery()->find($identifier);
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        $model = $this->createModel();

        return $model->newQuery()
            ->where($model->getAuthIdentifierName(), $identifier)
            ->where($model->getRememberTokenName(), $token)
            ->first();
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(UserContract $user, $token)
    {
        $user->setRememberToken($token);

        $user->save();
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = $this->createModel()->newQuery();

        foreach ($credentials as $key => $value) {
            if (! Str::contains($key, 'password')) {
                if($key == 'identifier') {
                    $query->where(function($query) use ($value){
                        $query->where('username', $value)
                              ->orWhere('email', $value);
                    });
                }else {
                    $query->where($key, $value);
                }
            }
        }
        
        return $query->first();
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plain = $credentials['password'];

        return $this->hasher->check($plain, $user->getAuthPassword());
    }

    /**
     * Create a new instance of the model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createModel()
    {
        return $this->model->newInstance();
    }

    /**
     * Scope of deleted datatables
     * 
     * @return self
     */
    public function dtScopeOfDeleted()
    {
        $this->dtModel = $this->dtModel->onlyTrashed();

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
                $roles[] = $this->roleRepository->findById( new AccountDomainModels\RoleId($role->id));
            }

            return new AccountDomainModels\User($id, $username, $email, $description, $roles);
        }
    }
}