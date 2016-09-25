<?php namespace Soda\Cms\Models;

use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;
use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    use OptionallyInApplicationTrait;
}
