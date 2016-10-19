<?php

namespace Soda\Cms\Database\Support\Interfaces;

use Illuminate\Database\Schema\Blueprint;

interface CanBuildDynamicModels
{
    public function createTable();
    public function deleteTable();
    public function getDynamicTableName();
    public function getDynamicModelTablePrefix();
    public function buildDynamicTable(Blueprint $table);
}
