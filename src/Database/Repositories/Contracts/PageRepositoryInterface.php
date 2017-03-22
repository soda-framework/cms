<?php

namespace Soda\Cms\Database\Repositories\Contracts;

use Soda\Cms\Database\Models\Contracts\PageInterface;

interface PageRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug($slug);

    public function getBlockTypes();

    public function getAvailableBlockTypes(PageInterface $page);

    public function getTypes($creatableOnly = false);

    public function getTree();

    public function getRoot();

    /**
     * @return PageInterface
     */
    public function createRoot();

    public function createStub($parentId = null, $pageTypeId = null);
}
