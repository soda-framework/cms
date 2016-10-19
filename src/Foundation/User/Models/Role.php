<?php namespace Soda\Cms\Foundation\User\Models;

use Illuminate\Database\Eloquent\Model;
use Soda\Cms\Foundation\Support\Models\Traits\OptionallyBoundToApplication;
use Soda\Cms\Foundation\Support\Models\Traits\RoleHasPermissions;
use Soda\Cms\Foundation\User\Interfaces\RoleInterface;

class Role extends Model implements RoleInterface
{
    use OptionallyBoundToApplication, RoleHasPermissions;
}
