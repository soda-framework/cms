<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterParentIdColumnInContentShortcutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('content_shortcuts', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->renameColumn('parent_id', 'parent_content_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content_shortcuts', function (Blueprint $table) {
            $table->dropForeign(['parent_content_type_id']);
            $table->renameColumn('parent_content_type_id', 'parent_id');
        });
    }
}
