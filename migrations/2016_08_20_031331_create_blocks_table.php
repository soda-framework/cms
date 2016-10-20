<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
            $table->string('name', 50);
            $table->text('description', 65535)->nullable();
            $table->integer('is_shared', 1)->unsigned()->default(0);
            $table->string('identifier', 50);
            $table->integer('block_type_id')->unsigned()->index('FK_blocks_block_types');
            $table->integer('application_id')->unsigned()->nullable()->index('FK_blocks_applications');
            $table->string('list_action')->nullable();
            $table->string('list_action_type')->nullable();
            $table->string('edit_action')->nullable();
            $table->string('edit_action_type')->nullable();
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
