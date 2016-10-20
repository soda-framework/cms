<?php
namespace Soda\Cms\Database\Pages\Interfaces;

use Franzose\ClosureTable\Contracts\EntityInterface;

interface PageInterface extends EntityInterface
{
    public function buildTree();
    public function getDynamicModel();
    public function getBlock($identifier);
    public function getBlockModel($identifier);
    public function blockModel($identifier);
    public function pageAttributes();
    public function getPageAttribute($attribute);
    public function generateSlug($slug);
}
