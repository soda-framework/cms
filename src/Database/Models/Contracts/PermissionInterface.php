<?php

namespace Soda\Cms\Database\Models\Contracts;

use Laratrust\Contracts\LaratrustPermissionInterface;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

interface PermissionInterface extends LaratrustPermissionInterface, AuditableContract
{
}
