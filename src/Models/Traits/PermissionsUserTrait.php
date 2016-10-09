<?php

namespace Soda\Cms\Models\Traits;

use Cache;
use Config;
use Illuminate\Cache\TaggableStore;
use Laratrust\Traits\LaratrustUserTrait;

trait PermissionsUserTrait
{
    use LaratrustUserTrait;

    /**
     * Boot the user model
     * Attach event listener to remove the many-to-many records when trying to delete
     * Will NOT delete any records if the user model uses soft deletes.
     *
     * @return void|bool
     */
    public static function bootLaratrustUserTrait()
    {
        $flushCache = function ($user) {
            $user->flushCache();

            return true;
        };

        // If the user doesn't use SoftDeletes
        if (method_exists(Config::get('auth.providers.users.model'), 'restored')) {
            static::restored($flushCache);
        }

        static::deleted($flushCache);
        static::saved($flushCache);

        static::deleting(function ($user) {
            if (!method_exists(Config::get('auth.providers.users.model'), 'bootSoftDeletes')) {
                $user->roles()->sync([]);
            }
        });
    }

    /**
     * Morphed Many-to-Many relations with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function roles()
    {
        return $this->morphToMany(
            Config::get('laratrust.role'),
            'user',
            Config::get('laratrust.role_user_table'),
            Config::get('laratrust.user_foreign_key'),
            Config::get('laratrust.role_foreign_key')
        );
    }

    //Big block of caching functionality.
    public function cachedRoles()
    {
        $cache = app('cache');

        if ($cache->getStore() instanceof TaggableStore) {
            $cache = $cache->tags(Config::get('laratrust.role_user_table'));
        }

        return $cache->remember($this->getCacheKey(), Config::get('cache.ttl'), function () {
            if (!$this->roles) {
                return $this->load('roles');
            }

            return $this->roles;
        });
    }

    /**
     * Flush the user's cache
     *
     * @return void
     */
    public function flushCache()
    {
        if (Cache::getStore() instanceof TaggableStore) {
            Cache::tags(Config::get('laratrust.role_user_table'))->flush();
        }

        Cache::forget($this->getCacheKey());
    }

    public function getCacheKey()
    {
        return 'laratrust_roles_for_'.$this->getTable().'_'.$this->getKey();
    }
}
