<?php

namespace Soda\Cms\Models;

use Cache;
use Config;
use Illuminate\Cache\TaggableStore;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use OptionallyInApplicationTrait, LaratrustUserTrait;
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
        $cache = app('cache');
        $cacheKey = 'laratrust_roles_for_user_'.$this->getKey();

        if($cache->getStore() instanceof TaggableStore) {
            $cache = $cache->tags(Config::get('laratrust.role_user_table'));
        }

        return $cache->remember($cacheKey, Config::get('cache.ttl'), function () {
            if (!$this->roles) {
                return $this->load('roles');
            }

            return $this->roles;
        });
    }

    /**
     * Flush the user's cache
     * @return void
     */
    public function flushCache()
    {
        if(Cache::getStore() instanceof TaggableStore) {
            Cache::tags(Config::get('laratrust.role_user_table'))->flush();
        }

        Cache::forget('laratrust_roles_for_user_' . $this->getKey());
    }
}
