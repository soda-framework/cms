<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPageTypeBlocksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page_type_block_types', function (Blueprint $table) {
            $table->foreign('block_type_id', 'FK_page_type_blocks_block_types')->references('id')->on('block_types')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::table('page_type_block_types', function (Blueprint $table) {
            $table->dropForeign('FK_page_type_block_types_block_types');
            $table->dropForeign('FK_page_type_block_types_page_types');
        });
    }

}
