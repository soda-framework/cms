<?php

namespace Soda\Cms\Database\Observers;

use Soda\Cms\Database\Models\DynamicContent;
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
    public function created(ContentInterface $content)
    {
        if ($content->getAttribute('content_type_id') !== null) {
            if (! $content->relationLoaded('type')) {
                $content->load('type');
            }

            DynamicContent::fromTable($content->getRelation('type')->getAttribute('identifier'))->fill(['content_id' => $content->getKey()])->save();
        }
    }
}
