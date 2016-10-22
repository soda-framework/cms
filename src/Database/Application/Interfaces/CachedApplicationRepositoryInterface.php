<?php

namespace Soda\Cms\Database\Application\Interfaces;

interface CachedApplicationRepositoryInterface
{
    public function findByUrl($url);

    public function getSettingsForApplication(ApplicationInterface $application);
}
