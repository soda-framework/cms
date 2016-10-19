<?php

namespace Soda\Cms\Foundation\Application\Interfaces;

interface CachedApplicationRepositoryInterface
{
    public function findByUrl($url);
}
