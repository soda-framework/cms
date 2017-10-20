<?php

namespace Soda\Cms\Database\Observers;

use Illuminate\Support\Facades\Schema;
use Soda\Cms\Database\Models\Contracts\CanBuildDynamicModels;

class DynamicModelObserver
{
    /**
     * Listen to the dynamic creating event.
     *
     * @param $type
     */
    public function creating(CanBuildDynamicModels $type)
    {
        $this->buildTable($type);
    }

    protected function buildTable(CanBuildDynamicModels $type)
    {
        if ($type->shouldDynamicTableExist() && ! $type->dynamicTableExists()) {
            $type->createTable();
        }
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
        if ($type->isDirty('identifier') && $type->getKey()) {
            $old_table = $type->getDynamicModelTablePrefix().$type->getOriginal('identifier');
            $new_table = $type->getDynamicModelTablePrefix().$type->getAttribute('identifier');

            if (Schema::hasTable($old_table)) {
                Schema::rename($old_table, $new_table);
            } else {
                $this->buildTable($type);
            }
        }
    }
}
