<?php namespace Soda\Cms\Database\User\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Database\Support\Models\Traits\OptionallyBoundToApplication;
use Soda\Cms\Database\Support\Models\Traits\RoleHasPermissions;
use Soda\Cms\Database\User\Interfaces\RoleInterface;

class Role extends Model implements RoleInterface
{
    use OptionallyBoundToApplication, RoleHasPermissions;
}
