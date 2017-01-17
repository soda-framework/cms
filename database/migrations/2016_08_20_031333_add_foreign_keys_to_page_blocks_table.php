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
        Schema::table('page_blocks', function (Blueprint $table) {
            $table->foreign('block_id', 'FK_page_blocks_blocks')->references('id')->on('blocks')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::table('page_blocks', function (Blueprint $table) {
            $table->dropForeign('FK_page_blocks_blocks');
            $table->dropForeign('FK_page_blocks_pages');
        });
    }
}
