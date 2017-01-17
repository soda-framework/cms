<?php

namespace Soda\Cms\Models;

use Cache;
use Config;
use Zizaco\Entrust\EntrustRole;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;

class Role extends EntrustRole
{
    use OptionallyInApplicationTrait;

    //Big block of caching functionality.
    public function cachedPermissions()
    {
        $rolePrimaryKey = $this->primaryKey;
        $cacheKey = 'entrust_permissions_for_role_'.$this->$rolePrimaryKey;

        return Cache::tags(Config::get('entrust.permission_role_table'))->remember($cacheKey, Config::get('cache.ttl'), function () {
            if (! $this->perms) {
                return $this->load('perms');
            }

            return $this->perms;
        });
    }
}
