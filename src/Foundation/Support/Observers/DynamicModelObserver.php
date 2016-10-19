<?php

namespace Soda\Cms\Foundation\Support\Observers;

use Schema;
use Soda\Cms\Foundation\Support\Interfaces\CanBuildDynamicModels;

class DynamicModelObserver
{
    /**
     * Listen to the dynamic creating event.
     *
     * @param $type
     */
    public function creating(CanBuildDynamicModels $type)
    {
        $type->createTable()->addFields($type->fields);
    }

    /**
     * Listen to the dynamic deleting event.
     *
     * @param $type
     */
    public function deleting(CanBuildDynamicModels $type)
    {
        $type->deleteTable();
    }

    public function saving(CanBuildDynamicModels $type)
    {
        if ($type->isDirty('identifier') && $type->id) {
            $old_table = $type->getDynamicModelTablePrefix().$type->getOriginal('identifier');
            $new_table = $type->getDynamicModelTablePrefix().$type->identifier;

            if (Schema::hasTable($old_table)) {
                Schema::rename($old_table, $new_table);
            }
        }
    }
}
