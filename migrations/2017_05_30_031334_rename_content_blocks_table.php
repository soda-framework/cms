<?php

use Illuminate\Database\Migrations\Migration;

class RenameContentBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('content_blocks', 'content_block_types');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('content_block_types', 'content_blocks');
    }
}
