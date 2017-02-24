<?php

namespace Soda\Cms\Database\Users\Interfaces;

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
