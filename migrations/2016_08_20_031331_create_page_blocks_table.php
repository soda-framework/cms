<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePageBlocksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_blocks', function (Blueprint $table) {
            $table->integer('page_id')->unsigned()->index('FK_page_blocks_pages');
            $table->integer('block_type_id')->unsigned()->index('FK_page_blocks_block_types');
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
        Schema::drop('page_blocks');
    }

}
