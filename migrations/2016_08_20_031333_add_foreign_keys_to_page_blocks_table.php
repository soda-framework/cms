<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToPageBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page_blocks', function(Blueprint $table) {
            $table->foreign('block_type_id', 'FK_page_blocks_block_types')->references('id')->on('block_types')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('page_id', 'FK_page_blocks_pages')->references('id')->on('pages')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('page_blocks', function(Blueprint $table) {
            $table->dropForeign('FK_page_blocks_block_types');
            $table->dropForeign('FK_page_blocks_pages');
        });
    }
}
