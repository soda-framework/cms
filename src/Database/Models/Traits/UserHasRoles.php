<?php

namespace Soda\Cms\Database\Models\Traits;

use Laratrust\Traits\LaratrustUserTrait;

trait UserHasRoles
{
    use LaratrustUserTrait;

    /**
     * Boot the user model
     * Attach event listener to remove the many-to-many records when trying to delete
     * Will NOT delete any records if the user model uses soft deletes.
     *
     * @return void|bool
     */
    public static function bootUserHasRolesTrait()
    {
        static::bootLaratrustUserTrait();
    }

    public function getCacheKey()
    {
        return 'soda.permissions.' . $this->getTable() . '.' . $this->getKey();
    }

    public function listRoles()
    {
        return $this->cachedRoles()->pluck('name', 'id');
    }
}
