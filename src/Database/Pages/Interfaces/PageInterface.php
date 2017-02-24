<?php

namespace Soda\Cms\Database\Pages\Interfaces;

use Franzose\ClosureTable\Contracts\EntityInterface;
use Soda\Cms\Database\Support\Interfaces\CanBeSorted;

interface PageInterface extends EntityInterface, CanBeSorted
{
    public function getDynamicModel();

    public function getBlockType($identifier);

    public function getBlock($identifier);

    public function block($identifier);

    public function pageAttributes();

    public function getPageAttribute($attribute);

    /**
     * @return string
     */
    public function generateSlug($slug);

    /**
     * @return boolean
     */
    public function isAllowedChildren();

    /**
     * @return boolean
     */
    public function canDelete();
}
