<?php

namespace Soda\Cms\Models\Observers;

use Soda\Cms\Models\Block;
use Soda\Cms\Models\ModelBuilder;

class BlockObserver
{
    /**
     * Listen to the Block saved event.
     *
     * @param \Soda\Cms\Models\Block $block
     */
    public function saved(Block $block)
    {
        if ($block->isDirty('is_shared')) {
            if (!$block->type) {
                $block->load('type');
            }
            ModelBuilder::fromTable('soda_'.$block->type->identifier)->where($block->getRelatedField(), $block->id)->update(['is_shared' => $block->is_shared]);
        }
    }
}
