<?php
namespace Soda\Cms\Foundation\Pages\Models;

use Soda\Cms\Foundation\Pages\Interfaces\DynamicPageInterface;
use Soda\Cms\Foundation\Pages\Interfaces\PageTypeInterface;
use Soda\Cms\Foundation\Support\Models\AbstractDynamicModel;
use Soda\Cms\Foundation\Support\Models\Traits\HasMedia;

class DynamicPage extends AbstractDynamicModel implements DynamicPageInterface
{
    use HasMedia;

    public function getDynamicModelTablePrefix()
    {
        return app(PageTypeInterface::class)->getDynamicModelTablePrefix();
    }
}
