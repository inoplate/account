<?php

namespace Inoplate\Account;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

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
    protected $fillable = ['name', 'slug'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Define users relationship
     * 
     * @return Model
     */
    public function users()
    {
        return $this->belongsToMany('Inoplate\Account\User', 'role_user');
    }

    /**
     * Define roles permissions relation
     * 
     * @return Model
     */

    public function permissions()
    {
        return DB::table('permission_role')->where('role_id', $this->id)->get();
    }

    /**
     * Sync permission
     * 
     * @param  array $permissions
     * @return void
     */
    public function syncPermissions($permissions)
    {
        DB::table('permission_role')->where('role_id', $this->id)->delete();

        $bulk = [];

        foreach ($permissions as $permission) {
            $bulk[] = ['role_id' => $this->id, 'permission_id' => $permission];
        }

        DB::table('permission_role')->insert($bulk);
    }
}