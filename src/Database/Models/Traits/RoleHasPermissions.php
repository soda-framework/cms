<?php

namespace Soda\Cms\Database\Models\Traits;

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
    public static function bootRoleHasPermissionsTrait()
    {
        static::bootLaratrustRoleTrait();
    }

    public function getCacheKey()
    {
        return 'soda.permissions.' . $this->getTable() . '.' . $this->getKey();
    }
}
