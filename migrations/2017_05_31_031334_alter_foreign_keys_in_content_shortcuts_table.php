<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterForeignKeysInContentShortcutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('content_shortcuts', function (Blueprint $table) {
            $table->foreign('parent_content_type_id', 'FK_content_shortcuts_content_type_parent')->references('id')->on('content_types')->onUpdate('CASCADE')->onDelete('SET NULL');
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
            $table->foreign('parent_id', 'FK_content_shortcuts_content')->references('id')->on('content')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }
}
