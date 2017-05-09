<?php

namespace Soda\Cms\Database\Observers;

use Soda\Cms\Database\Models\Contracts\ContentInterface;

class ContentObserver
{
    /**
     * TODO: when type/blocks are updated, invalidate cache:
     * soda.{app-id}.page.slug-{slug}.
     */

    /**
     * Listen to the Page saved event.
     *
     * @param ContentInterface $content
     */
    public function saved(ContentInterface $content)
    {
        if ($content->shouldDynamicTableExist() && ! $content->dynamicTableExists()) {
            $content->getRelation('type')->createTable();
        }
    }
}
