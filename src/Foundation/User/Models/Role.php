<?php namespace Soda\Cms\Foundation\User\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Foundation\Support\Traits\OptionallyInApplicationTrait;
use Soda\Cms\Foundation\User\Interfaces\RoleInterface;
use Soda\Cms\Foundation\User\Traits\RolePermissionsTrait;

class Role extends Model implements RoleInterface
{
    use OptionallyInApplicationTrait, RolePermissionsTrait;
}
