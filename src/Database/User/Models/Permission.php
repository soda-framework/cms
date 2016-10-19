<?php namespace Soda\Cms\Database\User\Models;

use Laratrust\LaratrustPermission;
use Soda\Cms\Database\Support\Models\Traits\OptionallyBoundToApplication;
use Soda\Cms\Database\User\Interfaces\PermissionInterface;

class Permission extends LaratrustPermission implements PermissionInterface
{
    use OptionallyBoundToApplication;
}
