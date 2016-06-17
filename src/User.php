<?php

namespace Inoplate\Account;

use Roseffendi\Authis\User as AuthorizableContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements AuthorizableContract
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Determine if auto increment is disabled
     * 
     * @var boolean
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'username', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Define roles relationship
     * 
     * @return Model
     */
    public function roles()
    {
        return $this->belongsToMany('Inoplate\Account\Role', 'role_user');
    }

    public function emailReset()
    {
        return $this->hasOne('Inoplate\Account\EmailReset');
    }

    /**
     * Retrieve user unique identifier
     * 
     * @return unique
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Scope of query to only include users of role given
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfRoles($query, $roles = null)
    {
        if((is_array($roles)) && (count($roles))) {
            return $query->whereHas('roles', function($query) use ($roles){
                $query->whereIn('id', $roles);
            });
        }
        
        return $query;
    }

    /**
     * Scope of query to only include users of active type given
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfStatus($query, $status = null)
    {
        if((is_null($status))||(strlen($status) === 0))
            return $query;
        
        return $query->where('active', $status);
    }

    /**
     * Retrieve user abilities
     * 
     * @return array
     */
    public function abilities()
    {
        $roles = $this->roles;
        $abilities = [];

        foreach ($roles as $role) {
            $permissions = $role->permissions();

            foreach ($permissions as $permission) {
                if( !isset($abilities[$permission->permission_id])) {
                    $abilities[] = $permission->permission_id;
                }
            }
        }

        return $abilities;
    }
}
