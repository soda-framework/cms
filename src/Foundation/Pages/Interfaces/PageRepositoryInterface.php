<?php
namespace Soda\Cms\Foundation\Pages\Interfaces;

use Soda\Cms\Foundation\Support\Interfaces\BaseRepositoryInterface;

interface PageRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug($slug);
    public function getTypes();
    public function getTree();
    public function getRoot();
    public function createRoot();
    public function createStub($parentId = null, $pageTypeId = null);
}
