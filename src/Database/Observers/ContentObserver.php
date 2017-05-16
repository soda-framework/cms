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
        if ($content->content_type_id !== null) {
            if (! $content->relationLoaded('type')) {
                $content->load('type');
            }

            $contentType = $content->getRelation('type');

            if ($contentType && $contentType->shouldDynamicTableExist() && ! $contentType->dynamicTableExists()) {
                $contentType->createTable();
            }
        }
    }
}
