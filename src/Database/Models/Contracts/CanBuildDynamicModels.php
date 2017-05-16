<?php

namespace Soda\Cms\Database\Models\Contracts;

use Illuminate\Database\Schema\Blueprint;

interface CanBuildDynamicModels
{
    /**
     * @return \Soda\Cms\Database\Models\Traits\BuildsDynamicModels
     */
    public function createTable();

    /**
     * @return \Soda\Cms\Database\Models\Traits\BuildsDynamicModels
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

    public function shouldDynamicTableExist();

    public function dynamicTableExists();
}
