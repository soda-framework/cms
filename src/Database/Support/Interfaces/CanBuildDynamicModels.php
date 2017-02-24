<?php

namespace Soda\Cms\Database\Support\Interfaces;

use Illuminate\Database\Schema\Blueprint;

interface CanBuildDynamicModels
{
    /**
     * @return \Soda\Cms\Database\Support\Models\Traits\BuildsDynamicModels
     */
    public function createTable();

    /**
     * @return \Soda\Cms\Database\Support\Models\Traits\BuildsDynamicModels
     */
    public function deleteTable();

    /**
     * @return string
     */
    public function getDynamicTableName();

    /**
     * @return string
     */
    public function getDynamicModelTablePrefix();

    /**
     * @return void
     */
    public function buildDynamicTable(Blueprint $table);
}
