<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToContentBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('content_blocks', function (Blueprint $table) {
            $table->foreign('block_type_id')->references('id')->on('block_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('content_id')->references('id')->on('content')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content_blocks', function (Blueprint $table) {
            $table->dropForeign(['block_type_id']);
            $table->dropForeign(['content_id']);
        });
    }
}
