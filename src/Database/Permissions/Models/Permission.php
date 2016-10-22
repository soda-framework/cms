<?php namespace Soda\Cms\Database\Permissions\Models;

use Laratrust\LaratrustPermission;
use Soda\Cms\Database\Support\Models\Traits\OptionallyBoundToApplication;
use Soda\Cms\Database\Permissions\Interfaces\PermissionInterface;

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
