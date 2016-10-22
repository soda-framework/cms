<?php namespace Soda\Cms\Database\Users\Models;

use Laratrust\LaratrustPermission;
use Soda\Cms\Database\Support\Models\Traits\OptionallyBoundToApplication;
use Soda\Cms\Database\Users\Interfaces\PermissionInterface;

class Permission extends LaratrustPermission implements PermissionInterface
{
    use OptionallyBoundToApplication;
}
