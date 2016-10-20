<?php

namespace Soda\Cms\Database\Blocks\Observers;

use Soda\Cms\Database\Blocks\Models\DynamicBlock;
use Soda\Cms\Database\Blocks\Interfaces\BlockInterface;

class BlockObserver
{
    /**
     * Listen to the Block saved event.
     *
     * @param BlockInterface $block
     */
    public function saved(BlockInterface $block)
    {
        if ($block->isDirty('is_shared')) {
            if (!$block->relationLoaded('type')) {
                $block->load('type');
            }

            DynamicBlock::fromTable($block->getRelation('type')->getAttribute('identifier'))->where('block_id', $block->getKey())->update(['is_shared' => $block->getAttribute('is_shared')]);
        }
    }
}
