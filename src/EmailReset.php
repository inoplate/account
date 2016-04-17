<?php

namespace Inoplate\Account;

use Illuminate\Database\Eloquent\Model;

class EmailReset extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'email_resets';

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
    protected $fillable = ['user_id'];

    /**
     * Define user relation
     * 
     * @return Model
     */
    public function user()
    {
        $this->belongsTo('Inoplate\Account\User');
    }
}