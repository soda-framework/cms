<?php

namespace Soda\Cms\Database\Support\Models\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Cache\TaggableStore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Laratrust\Traits\LaratrustRoleTrait;

trait RoleHasPermissions
{
    use LaratrustRoleTrait;

    /**
     * Boot the role model
     * Attach event listener to remove the many-to-many records when trying to delete
     * Will NOT delete any records if the role model uses soft deletes.
     *
     * @return void|bool
     */
    public static function bootPermissionsRoleTrait()
    {
        $flushCache = function($role) {
            $role->flushCache();

            return true;
        };

        // If the role doesn't use SoftDeletes
        if (method_exists(Config::get('laratrust.role'), 'restored')) {
            static::restored($flushCache);
        }

        static::deleted($flushCache);
        static::saved($flushCache);

        static::deleting(function($role) {
            if (!method_exists(Config::get('laratrust.role'), 'bootSoftDeletes')) {
                DB::table(Config::get('laratrust.role_user_table'))->where('role_id', $role->getKey())->delete();
                $role->permissions()->sync([]);
            }
        });
    }

    //Big block of caching functionality.
    public function cachedPermissions()
    {
        if ($ttl = config('soda.cache.permissions')) {
            $cache = app('cache');

            if ($cache->getStore() instanceof TaggableStore) {
                $cache = $cache->tags(Config::get('laratrust.permission_role_table'));
            }

            return $cache->remember($this->getCacheKey(), is_int($ttl) ? $ttl : config('soda.cache.default-ttl'), function() {
                if (!$this->relationLoaded('permissions')) {
                    $this->load('permissions');
                }

                return $this->getRelation('permissions');
            });
        }

        if (!$this->relationLoaded('permissions')) {
            $this->load('permissions');
        }

        return $this->getRelation('permissions');
    }

    /**
     * Flush the role's cache.
     *
     * @return void
     */
    public function flushCache()
    {
        if (Cache::getStore() instanceof TaggableStore) {
            Cache::tags(Config::get('laratrust.permission_role_table'))->flush();
        }

        Cache::forget($this->getCacheKey());
    }

    public function getCacheKey()
    {
        return 'soda.permissions.'.$this->getTable().'.'.$this->getKey();
    }
}
