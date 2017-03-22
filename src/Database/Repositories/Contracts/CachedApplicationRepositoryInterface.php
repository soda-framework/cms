<?php

namespace Soda\Cms\Database\Repositories\Contracts;

use Soda\Cms\Database\Models\Contracts\ApplicationInterface;

interface CachedApplicationRepositoryInterface
{
    public function findByUrl($url);

    public function getSettingsForApplication(ApplicationInterface $application);
}
