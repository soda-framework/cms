<?php

namespace Soda\Cms\Database\Models\Contracts;

use Laratrust\Contracts\LaratrustRoleInterface;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

interface RoleInterface extends LaratrustRoleInterface, AuditableContract
{
}
