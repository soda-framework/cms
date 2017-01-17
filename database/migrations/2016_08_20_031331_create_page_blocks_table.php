<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->integer('page_id')->unsigned()->index('FK_page_blocks_pages');
            $table->integer('block_id')->unsigned()->index('FK_page_blocks_blocks');
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
