<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_blocks', function (Blueprint $table) {
            $table->integer('content_id')->unsigned()->index('FK_content_blocks_content');
            $table->integer('block_type_id')->unsigned()->index('FK_content_blocks_block_types');
            $table->integer('min_blocks')->unsigned()->nullable();
            $table->integer('max_blocks')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('content_blocks');
    }
}
