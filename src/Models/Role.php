<?php namespace Soda\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Laratrust\Contracts\LaratrustRoleInterface;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;
use Soda\Cms\Models\Traits\PermissionsRoleTrait;

class Role extends Model implements LaratrustRoleInterface
{
    use OptionallyInApplicationTrait, PermissionsRoleTrait;
}
