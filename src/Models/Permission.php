<?php

namespace Soda\Cms\Models;

use Laratrust\LaratrustPermission;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;

class Permission extends LaratrustPermission
{
    use OptionallyInApplicationTrait;
}
