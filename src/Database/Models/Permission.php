<?php

namespace Soda\Cms\Database\Models;

use Laratrust\LaratrustPermission;
use Soda\Cms\Database\Models\Traits\Auditable;
use Soda\Cms\Database\Models\Contracts\PermissionInterface;
use Soda\Cms\Database\Models\Traits\OptionallyBoundToApplication;

class Permission extends LaratrustPermission implements PermissionInterface
{
    use Auditable, OptionallyBoundToApplication;

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

    /**
     * Attributes to exclude from the Audit.
     *
     * @var array
     */
    protected $auditExclude = [
    ];
}
