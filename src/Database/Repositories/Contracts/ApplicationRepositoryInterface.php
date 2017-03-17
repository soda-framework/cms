<?php

namespace Soda\Cms\Database\Repositories\Contracts;

use Soda\Cms\Database\Models\Contracts\ApplicationInterface;

interface ApplicationRepositoryInterface
{
    public function findById($id);

    public function findByUrl($url);

    public function getSettingsForApplication(ApplicationInterface $application);
}
