<?php

namespace Soda\Cms\Database\Repositories;

use Soda\Cms\Database\Models\Contracts\PageTypeInterface;
use Soda\Cms\Database\Models\Contracts\BlockTypeInterface;
use Soda\Cms\Database\Repositories\Contracts\PageTypeBlockRepositoryInterface;

class PageTypeBlockRepository implements PageTypeBlockRepositoryInterface
{
    protected $pageTypes;
    protected $blockTypes;

    public function __construct(PageTypeInterface $pageTypes, BlockTypeInterface $blockTypes)
    {
        $this->pageTypes = $pageTypes;
        $this->blockTypes = $blockTypes;
    }

    public function attach($pageTypeId, $blockTypeId, $pageTypeBlockTypeParams = [])
    {
        $pageType = $this->pageTypes->findOrFail($pageTypeId);
        $pageType->blockTypes()->attach($blockTypeId, $pageTypeBlockTypeParams);
    }

    public function detach($pageTypeId, $blockTypeId)
    {
        $pageType = $this->pageTypes->findOrFail($pageTypeId);
        $pageType->blockTypes()->detach($blockTypeId);
    }
}
