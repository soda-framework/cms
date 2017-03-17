<?php

namespace Soda\Cms\Database\Repositories\Contracts;

interface CachedPageRepositoryInterface
{
    public function findBySlug($slug);
}
