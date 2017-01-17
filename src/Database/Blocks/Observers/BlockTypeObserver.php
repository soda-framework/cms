<?php

namespace Soda\Cms\Database\Blocks\Observers;

use Soda\Cms\Database\Blocks\Models\DynamicBlock;
use Soda\Cms\Database\Blocks\Interfaces\BlockTypeInterface;

class BlockTypeObserver
{
    /**
     * Listen to the Block saved event.
     *
     * @param BlockTypeInterface $blockType
     */
    public function saved(BlockTypeInterface $blockType)
    {
        if ($blockType->isDirty('is_shared')) {
            DynamicBlock::fromTable($blockType->getAttribute('identifier'))->where('block_id', $blockType->getKey())->update(['is_shared' => $blockType->getAttribute('is_shared')]);
        }
    }
}
