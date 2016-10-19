<?php
namespace Soda\Cms\Database\Pages\Interfaces;

interface CachedPageRepositoryInterface
{
    public function findBySlug($slug);
}
