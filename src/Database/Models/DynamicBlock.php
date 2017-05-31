<?php

namespace Soda\Cms\Database\Models;

use Soda\Cms\Database\Models\Traits\HasMedia;
use Soda\Cms\Database\Models\Contracts\DynamicBlockInterface;
use Soda\Cms\Database\Models\Traits\Sortable;

class DynamicBlock extends AbstractDynamicModel implements DynamicBlockInterface
{
    use HasMedia, Sortable;

    /**
     * Attributes to exclude from the Audit.
     *
     * @var array
     */
    protected $auditExclude = [
    ];

    public function getDynamicModelTablePrefix()
    {
        return (new BlockType)->getDynamicModelTablePrefix();
    }
}
