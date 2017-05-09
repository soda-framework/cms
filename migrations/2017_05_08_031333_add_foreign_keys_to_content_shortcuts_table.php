<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToContentShortcutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('content_shortcuts', function (Blueprint $table) {
            $table->foreign('application_id', 'FK_content_shortcuts_applications')->references('id')->on('applications')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('content_type_id', 'FK_content_shortcuts_content_types')->references('id')->on('content_types')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign('parent_id', 'FK_content_shortcuts_content')->references('id')->on('content')->onUpdate('CASCADE')->onDelete('SET NULL');
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
            $table->dropForeign('FK_content_shortcuts_applications');
            $table->dropForeign('FK_content_shortcuts_content_types');
            $table->dropForeign('FK_content_shortcuts_content');
        });
    }
}
