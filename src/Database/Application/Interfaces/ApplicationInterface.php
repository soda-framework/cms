<?php

namespace Soda\Cms\Database\Application\Interfaces;

interface ApplicationInterface
{
    public function getSettings();

    public function getSetting($setting);
}
