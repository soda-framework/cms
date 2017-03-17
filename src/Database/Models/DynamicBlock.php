<?php

namespace Soda\Cms\Database\Models;

use Soda\Cms\Database\Models\Traits\HasMedia;
use Soda\Cms\Database\Models\Contracts\DynamicBlockInterface;

class DynamicBlock extends AbstractDynamicModel implements DynamicBlockInterface
{
    use HasMedia;

    public function getDynamicModelTablePrefix()
    {
        return (new BlockType)->getDynamicModelTablePrefix();
    }
}
