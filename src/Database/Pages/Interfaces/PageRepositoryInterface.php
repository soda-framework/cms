<?php
namespace Soda\Cms\Database\Pages\Interfaces;

use Soda\Cms\Database\Support\Interfaces\BaseRepositoryInterface;

interface PageRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug($slug);

    public function getBlockTypes();

    public function getAvailableBlockTypes(PageInterface $page);

    public function getTypes($creatableOnly = false);

    public function getTree();

    public function getRoot();

    public function createRoot();

    public function createStub($parentId = null, $pageTypeId = null);
}
