<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('content', function (Blueprint $table) {
            $table->foreign('application_id')->references('id')->on('applications')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('content_type_id')->references('id')->on('content_types')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('parent_id')->references('id')->on('content')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content', function (Blueprint $table) {
            $table->dropForeign(['application_id']);
            $table->dropForeign(['content_type_id']);
            $table->dropForeign(['parent_id']);
        });
    }
}
