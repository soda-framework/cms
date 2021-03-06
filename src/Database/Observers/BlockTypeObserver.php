<?php

namespace Soda\Cms\Database\Observers;

use Soda\Cms\Database\Models\DynamicBlock;
use Soda\Cms\Database\Models\Contracts\BlockTypeInterface;

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
            DynamicBlock::fromTable($blockType->getAttribute('identifier'))->update(['is_shared' => $blockType->getAttribute('is_shared')]);
        }

        if ($blockType->isDirty('identifier')) {
            DynamicBlock::fromTable($blockType->getAttribute('identifier'))->update(['is_shared' => $blockType->getAttribute('is_shared')]);
        }

        if (! $blockType->dynamicTableExists()) {
            $blockType->createTable();
        }
    }
}
