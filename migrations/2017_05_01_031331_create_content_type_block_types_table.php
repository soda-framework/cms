<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTypeBlockTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_type_block_types', function (Blueprint $table) {
            $table->integer('content_type_id')->unsigned();
            $table->integer('block_type_id')->unsigned();
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
        Schema::drop('content_type_block_types');
    }
}
