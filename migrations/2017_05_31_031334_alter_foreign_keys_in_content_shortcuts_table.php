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
            $table->foreign('parent_content_type_id')->references('id')->on('content_types')->onUpdate('cascade')->onDelete('set null');
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
            $table->foreign('parent_id')->references('id')->on('content')->onUpdate('cascade')->onDelete('set null');
        });
    }
}
