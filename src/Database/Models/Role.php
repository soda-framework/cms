<?php

namespace Soda\Cms\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Database\Models\Contracts\RoleInterface;
use Soda\Cms\Database\Models\Traits\RoleHasPermissions;
use Soda\Cms\Database\Models\Traits\OptionallyBoundToApplication;

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
        'level',
    ];
}
