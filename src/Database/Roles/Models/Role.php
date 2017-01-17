<?php

namespace Soda\Cms\Database\Roles\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Database\Roles\Interfaces\RoleInterface;
use Soda\Cms\Database\Support\Models\Traits\RoleHasPermissions;
use Soda\Cms\Database\Support\Models\Traits\OptionallyBoundToApplication;

class Role extends Model implements RoleInterface
{
    use OptionallyBoundToApplication, RoleHasPermissions;

    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];
}
