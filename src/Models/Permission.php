<?php

namespace Soda\Cms\Models;

use Zizaco\Entrust\EntrustPermission;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;

class Permission extends EntrustPermission
{
    use OptionallyInApplicationTrait;
}
