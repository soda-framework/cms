<?php

namespace Soda\Cms\Database\Pages\Models;

use Soda\Cms\Database\Support\Models\Traits\HasMedia;
use Soda\Cms\Database\Support\Models\AbstractDynamicModel;
use Soda\Cms\Database\Pages\Interfaces\DynamicPageInterface;

class DynamicPage extends AbstractDynamicModel implements DynamicPageInterface
{
    use HasMedia;

    public function getDynamicModelTablePrefix()
    {
        return app('soda.page-type.model')->getDynamicModelTablePrefix();
    }
}
