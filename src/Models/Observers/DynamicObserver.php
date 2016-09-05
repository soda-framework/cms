<?php

namespace Soda\Cms\Models\Observers;

use Schema;
use Soda\Cms\Models\AbstractDynamicType;
use Soda\Cms\Models\Block;
use Soda\Cms\Models\ModelBuilder;

class DynamicObserver
{
    /**
     * Listen to the dynamic creating event.
     *
     * @param $type
     */
    public function creating(AbstractDynamicType $type)
    {
        $type->createTable()->addFields($type->fields);
    }

    /**
     * Listen to the dynamic deleting event.
     *
     * @param $type
     */
    public function deleting(AbstractDynamicType $type)
    {
        $type->deleteTable();
    }

    public function saving(AbstractDynamicType $type)
    {
        if ($type->isDirty('identifier')) {
            $old_table = 'soda_' . $type->getOriginal('identifier');
            $new_table = 'soda_' . $type->identifier;

            Schema::rename($old_table, $new_table);
        }
    }
}
