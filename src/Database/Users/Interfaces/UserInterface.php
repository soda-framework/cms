<?php

namespace Soda\Cms\Database\Users\Interfaces;

use Laratrust\Contracts\LaratrustUserInterface;

interface UserInterface extends LaratrustUserInterface
{
    public function updateLoginTimestamp();
    public function listRoles();
}
