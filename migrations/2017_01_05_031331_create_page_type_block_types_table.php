<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageTypeBlockTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_type_block_types', function(Blueprint $table) {
            $table->integer('page_type_id')->unsigned()->index('FK_page_type_block_types_page_types');
            $table->integer('block_type_id')->unsigned()->index('FK_page_type_block_types_block_types');
            $table->integer('min_blocks')->nullable()->unsigned();
            $table->integer('max_blocks')->nullable()->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('page_type_block_types');
    }
}
