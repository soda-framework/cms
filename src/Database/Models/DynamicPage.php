<?php

namespace Soda\Cms\Database\Models;

use Soda\Cms\Database\Models\Traits\HasMedia;
use Soda\Cms\Database\Models\Contracts\DynamicPageInterface;

class DynamicPage extends AbstractDynamicModel implements DynamicPageInterface
{
    use HasMedia;

    public function getDynamicModelTablePrefix()
    {
        return (new PageType)->getDynamicModelTablePrefix();
    }
}
