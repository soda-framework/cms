<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageTypeBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_type_blocks', function (Blueprint $table) {
            $table->integer('page_type_id')->unsigned()->index('FK_page_type_blocks_page_types');
            $table->integer('block_id')->unsigned()->index('FK_page_type_blocks_blocks');
            $table->integer('can_create')->unsigned();
            $table->integer('can_delete')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('page_type_blocks');
    }
}
