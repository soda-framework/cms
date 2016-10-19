<?php
namespace Soda\Cms\Foundation\Pages\Interfaces;

interface CachedPageRepositoryInterface
{
    public function findBySlug($slug);
}
