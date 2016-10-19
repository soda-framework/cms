<?php

namespace Soda\Cms\Foundation\Blocks\Observers;

use Soda\Cms\Foundation\Blocks\Blocks\Models\DynamicBlock;
use Soda\Cms\Foundation\Blocks\Models\Interfaces\BlockInterface;

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

            DynamicBlock::fromTable($block->type->identifier)->where('block_id', $block->id)->update(['is_shared' => $block->is_shared]);
        }
    }
}
