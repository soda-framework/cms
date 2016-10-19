<?php

namespace Soda\Cms\Foundation\Pages\Models\Observers;

use Soda\Cms\Foundation\Pages\Interfaces\PageInterface;
use Soda\Cms\Foundation\Pages\Models\DynamicPage;

class PageObserver
{
    /**
     * TODO: when type/blocks are updated, invalidate cache:
     * soda.{app-id}.page.slug-{slug}
     */

    /**
     * Listen to the Page saved event.
     *
     * @param PageInterface $page
     */
    public function created(PageInterface $page)
    {
        if ($page->page_type_id !== null) {
            if(!$page->relationLoaded('type')) {
                $page->load('type');
            }

            DynamicPage::fromTable($page->type->identifier)->fill(['page_id' => $page->id])->save();
        }
    }
}
