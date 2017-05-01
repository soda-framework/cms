<?php

namespace Soda\Cms\Database\Repositories;

use Soda\Cms\Database\Models\Contracts\ContentTypeInterface;
use Soda\Cms\Database\Models\Contracts\BlockTypeInterface;
use Soda\Cms\Database\Repositories\Contracts\ContentTypeBlockRepositoryInterface;

class ContentTypeBlockRepository implements ContentTypeBlockRepositoryInterface
{
    protected $contentTypes;
    protected $blockTypes;

    public function __construct(ContentTypeInterface $contentTypes, BlockTypeInterface $blockTypes)
    {
        $this->contentTypes = $contentTypes;
        $this->blockTypes = $blockTypes;
    }

    public function attach($contentTypeId, $blockTypeId, $contentTypeBlockTypeParams = [])
    {
        $contentType = $this->contentTypes->findOrFail($contentTypeId);
        $contentType->blockTypes()->attach($blockTypeId, $contentTypeBlockTypeParams);
    }

    public function detach($contentTypeId, $blockTypeId)
    {
        $contentType = $this->contentTypes->findOrFail($contentTypeId);
        $contentType->blockTypes()->detach($blockTypeId);
    }
}
