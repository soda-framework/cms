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
            $table->dropForeign('FK_content_shortcuts_content');
            $table->dropIndex('content_shortcuts_parent_id_foreign');
            $table->renameColumn('parent_id', 'parent_content_type_id');
            $table->index('parent_content_type_id', 'FK_content_shortcuts_parent_id_foreign');
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
            $table->dropForeign('FK_content_shortcuts_parent_id_foreign');
            $table->dropIndex('FK_content_shortcuts_parent_id_foreign');
            $table->renameColumn('parent_content_type_id', 'parent_id');
            $table->index('parent_id', 'content_shortcuts_parent_id_foreign');
        });
    }
}
