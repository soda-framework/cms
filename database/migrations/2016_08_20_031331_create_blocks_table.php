<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 45);
            $table->text('description', 65535)->nullable();
            $table->string('identifier');
            $table->integer('status')->unsigned();
            $table->integer('application_id')->unsigned()->nullable()->index('FK_blocks_applications');
            $table->integer('block_type_id')->unsigned()->nullable()->index('FK_blocks_block_types');
            $table->integer('is_shared')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('blocks');
    }
}
