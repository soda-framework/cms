<?php

namespace Soda\Cms\Database\Models;

use Laratrust\LaratrustPermission;
use Soda\Cms\Database\Models\Contracts\PermissionInterface;
use Soda\Cms\Database\Models\Traits\OptionallyBoundToApplication;

class Permission extends LaratrustPermission implements PermissionInterface
{
    use OptionallyBoundToApplication;

    protected $table = 'permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'category',
    ];
}
