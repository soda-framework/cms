<?php

namespace Soda\Cms\Models\Observers;

use Schema;
use Soda\Cms\Models\AbstractDynamicType;

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
        if ($type->isDirty('identifier') && $type->id) {
            $old_table = 'soda_'.$type->getOriginal('identifier');
            $new_table = 'soda_'.$type->identifier;

            if (Schema::hasTable($old_table)) {
                Schema::rename($old_table, $new_table);
            }
        }
    }
}
