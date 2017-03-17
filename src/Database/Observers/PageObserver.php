<?php

namespace Soda\Cms\Database\Observers;

use Soda\Cms\Database\Models\DynamicPage;
use Soda\Cms\Database\Repositories\Contracts\PageInterface;

class PageObserver
{
    /**
     * TODO: when type/blocks are updated, invalidate cache:
     * soda.{app-id}.page.slug-{slug}.
     */

    /**
     * Listen to the Page saved event.
     *
     * @param PageInterface $page
     */
    public function created(PageInterface $page)
    {
        if ($page->getAttribute('page_type_id') !== null) {
            if (! $page->relationLoaded('type')) {
                $page->load('type');
            }

            DynamicPage::fromTable($page->getRelation('type')->getAttribute('identifier'))->fill(['page_id' => $page->getKey()])->save();
        }
    }
}
