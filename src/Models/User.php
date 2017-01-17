<?php

namespace Soda\Cms\Models;

use Cache;
use Config;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;

class User extends Authenticatable
{
    use OptionallyInApplicationTrait, EntrustUserTrait;
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    //Big block of caching functionality.
    public function cachedRoles()
    {
        $userPrimaryKey = $this->primaryKey;
        $cacheKey = 'entrust_roles_for_user_'.$this->$userPrimaryKey;

        return Cache::tags(Config::get('entrust.role_user_table'))->remember($cacheKey, Config::get('cache.ttl'), function () {
            if (! $this->roles) {
                return $this->load('roles');
            }

            return $this->roles;
        });
    }
}
