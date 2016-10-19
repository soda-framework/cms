<?php namespace Soda\Cms\Foundation\User\Models;

use Laratrust\LaratrustPermission;
use Soda\Cms\Foundation\Support\Traits\OptionallyInApplicationTrait;
use Soda\Cms\Foundation\User\Interfaces\PermissionInterface;

class Permission extends LaratrustPermission implements PermissionInterface
{
    use OptionallyInApplicationTrait;
}
