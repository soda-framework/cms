<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToPageTypeBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page_type_blocks', function (Blueprint $table) {
            $table->foreign('block_id', 'FK_page_type_blocks_blocks')->references('id')->on('blocks')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('page_type_id', 'FK_page_type_blocks_page_types')->references('id')->on('page_types')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('page_type_blocks', function (Blueprint $table) {
            $table->dropForeign('FK_page_type_blocks_blocks');
            $table->dropForeign('FK_page_type_blocks_page_types');
        });
    }
}
