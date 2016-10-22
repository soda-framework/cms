<?php

namespace Soda\Cms\Database\Pages\Models\Observers;

use Soda\Cms\Database\Pages\Interfaces\PageInterface;
use Soda\Cms\Database\Pages\Models\DynamicPage;

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
        if ($page->getAttribute('page_type_id') !== null) {
            if (!$page->relationLoaded('type')) {
                $page->load('type');
            }

            DynamicPage::fromTable($page->getRelation('type')->getAttribute('identifier'))->fill(['page_id' => $page->getKey()])->save();
        }
    }
}
