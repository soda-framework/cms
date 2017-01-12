<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePageTypeBlockTypesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_type_block_types', function (Blueprint $table) {
            $table->integer('page_type_id')->unsigned()->index('FK_page_type_block_types_page_types');
            $table->integer('block_type_id')->unsigned()->index('FK_page_type_block_types_block_types');
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
        Schema::drop('page_type_block_types');
    }

}
