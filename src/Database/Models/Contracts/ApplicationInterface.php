<?php

namespace Soda\Cms\Database\Models\Contracts;

interface ApplicationInterface
{
    public function getSettings();

    public function getSetting($setting);
}
