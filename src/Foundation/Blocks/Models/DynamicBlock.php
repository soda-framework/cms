<?php
namespace Soda\Cms\Foundation\Blocks\Models;

use Soda\Cms\Foundation\Blocks\Interfaces\BlockTypeInterface;
use Soda\Cms\Foundation\Blocks\Interfaces\DynamicBlockInterface;
use Soda\Cms\Foundation\Support\Models\AbstractDynamicModel;
use Soda\Cms\Foundation\Support\Models\Traits\HasMedia;

class DynamicBlock extends AbstractDynamicModel implements DynamicBlockInterface
{
    use HasMedia;

    public function getDynamicModelTablePrefix()
    {
        return app(BlockTypeInterface::class)->getDynamicModelTablePrefix();
    }
}
