<?php

namespace Soda\Cms\Models\Observers;

use Soda\Cms\Models\ModelBuilder;
use Soda\Cms\Models\Page;

class PageObserver
{
    /**
     * TODO: when type/blocks are updated, invalidate cache:
     * soda.{app-id}.page.slug-{slug}
     */

    /**
     * Listen to the Page saved event.
     *
     * @param \Soda\Cms\Models\Page $page
     */
    public function created(Page $page)
    {
        if ($page->page_type_id !== null) {
            //if(!$page->type) $page->load('type');
            ModelBuilder::fromTable('soda_'.$page->type->identifier)->forceFill(['page_id' => $page->id])->save();
        }
    }
}
