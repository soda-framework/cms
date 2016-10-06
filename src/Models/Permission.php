<?php namespace Soda\Cms\Models;

use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;
use Laratrust\LaratrustPermission;

class Permission extends LaratrustPermission
{
    use OptionallyInApplicationTrait;
}
