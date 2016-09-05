<?php

namespace Soda\Cms\Models\Observers;

use Soda\Cms\Models\ModelBuilder;
use Soda\Cms\Models\Page;

class PageObserver
{
    /**
     * Listen to the Page saved event.
     *
     * @param \Soda\Cms\Models\Page $page
     */
    public function created(Page $page)
    {
        ModelBuilder::fromTable('soda_' . $page->type->identifier)->create(['page_id' => $page->id]);
    }
}
