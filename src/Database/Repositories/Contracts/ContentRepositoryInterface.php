<?php

namespace Soda\Cms\Database\Repositories\Contracts;

use Soda\Cms\Database\Models\Contracts\ContentInterface;

interface ContentRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug($slug);

    public function listFolder($contentId = null);

    public function getBlockTypes();

    public function getAvailableBlockTypes(ContentInterface $content);

    public function getTypes($creatableOnly = false);

    public function createStub($parentId = null, $contentTypeId = null);
}
