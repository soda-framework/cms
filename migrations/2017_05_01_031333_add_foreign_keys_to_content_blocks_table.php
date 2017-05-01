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
            $table->foreign('block_type_id', 'FK_content_blocks_block_types')->references('id')->on('block_types')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('content_id', 'FK_content_blocks_content')->references('id')->on('content')->onUpdate('CASCADE')->onDelete('CASCADE');
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
            $table->dropForeign('FK_content_blocks_block_types');
            $table->dropForeign('FK_content_blocks_content');
        });
    }
}
