<?php
namespace Soda\Cms\Database\Blocks\Models;

use Soda\Cms\Database\Blocks\Interfaces\BlockTypeInterface;
use Soda\Cms\Database\Blocks\Interfaces\DynamicBlockInterface;
use Soda\Cms\Database\Support\Models\AbstractDynamicModel;
use Soda\Cms\Database\Support\Models\Traits\HasMedia;

class DynamicBlock extends AbstractDynamicModel implements DynamicBlockInterface
{
    use HasMedia;

    public function getDynamicModelTablePrefix()
    {
        return app('soda.block-type.model')->getDynamicModelTablePrefix();
    }
}
