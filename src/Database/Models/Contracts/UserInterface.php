<?php

namespace Soda\Cms\Database\Models\Contracts;

use Laratrust\Contracts\LaratrustUserInterface;

interface UserInterface extends LaratrustUserInterface
{
    /**
     * @return void
     */
    public function updateLoginTimestamp();

    public function listRoles();

    public function getLevel();
}
