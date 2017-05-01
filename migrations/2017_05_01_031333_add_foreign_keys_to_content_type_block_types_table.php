<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToContentTypeBlockTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('content_type_block_types', function (Blueprint $table) {
            $table->foreign('block_type_id', 'FK_content_type_blocks_block_types')->references('id')->on('block_types')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('content_type_id', 'FK_content_type_blocks_content_types')->references('id')->on('content_types')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content_type_block_types', function (Blueprint $table) {
            $table->dropForeign('FK_content_type_blocks_block_types');
            $table->dropForeign('FK_content_type_blocks_content_types');
        });
    }
}
