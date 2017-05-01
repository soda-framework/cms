<?php

namespace Soda\Cms\Database\Models;

use Soda\Cms\Database\Models\Traits\HasMedia;
use Soda\Cms\Database\Models\Contracts\DynamicContentInterface;

class DynamicContent extends AbstractDynamicModel implements DynamicContentInterface
{
    use HasMedia;

    /**
     * Attributes to exclude from the Audit.
     *
     * @var array
     */
    protected $auditExclude = [
    ];

    public function getDynamicModelTablePrefix()
    {
        return (new ContentType)->getDynamicModelTablePrefix();
    }
}
