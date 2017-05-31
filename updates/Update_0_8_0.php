<?php

namespace Soda\Updater\Cms;

use Composer\Semver\Comparator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Soda\Cms\Database\Models\BlockType;

class Update_0_8_0
{
    public function shouldRun()
    {
        return Comparator::greaterThan($this->currentVersion, '0.7.0');
    }

    public function run()
    {
        foreach(BlockType::withoutGlobalScopes()->get() as $blockType) {
            // Reset identifier
            $blockType->identifier = $blockType->identifier;
            $blockType->save();

            $tableName = $blockType->getDynamicTableName();

            // Add position column
            if(!Schema::hasColumn($tableName, 'position')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->integer('position')->unsigned()->nullable()->default('1')->after('content_id');
                });

                DB::unprepared("SET @current_group := NULL;");
                DB::unprepared("SET @current_count := NULL;");
                DB::unprepared("UPDATE `$tableName` SET `position` = CASE WHEN @current_group = `content_id` THEN @current_count := @current_count + 1 WHEN @current_group := `content_id` THEN @current_count := 1 END ORDER BY `content_id`, `created_at` DESC, `updated_at` DESC;");
            }
        }
    }
}
