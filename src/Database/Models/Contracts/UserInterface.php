<?php

namespace Soda\Cms\Database\Models\Contracts;

use Laratrust\Contracts\LaratrustUserInterface;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

interface UserInterface extends LaratrustUserInterface, AuditableContract
{
    /**
     * @return void
     */
    public function updateLoginTimestamp();

    public function listRoles();

    public function getLevel();
}
