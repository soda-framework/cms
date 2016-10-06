<?php namespace Soda\Cms\Models;

use Cache;
use Config;
use Illuminate\Cache\TaggableStore;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;
use Laratrust\LaratrustRole;

class Role extends LaratrustRole
{
    use OptionallyInApplicationTrait;

    //Big block of caching functionality.
    public function cachedPermissions()
    {
        $cache = app('cache');
        $cacheKey = 'laratrust_permissions_for_role_'.$this->getKey();

        if($cache->getStore() instanceof TaggableStore) {
            $cache = $cache->tags(Config::get('laratrust.permission_role_table'));
        }

        return $cache->remember($cacheKey, Config::get('cache.ttl'), function () {
            if (!$this->permissions) {
                return $this->load('permissions');
            }

            return $this->permissions;
        });
    }

    /**
     * Flush the role's cache
     * @return void
     */
    public function flushCache()
    {
        if(Cache::getStore() instanceof TaggableStore) {
            Cache::tags(Config::get('laratrust.permission_role_table'))->flush();
        }

        Cache::forget('laratrust_permissions_for_role_' . $this->getKey());
    }
}
