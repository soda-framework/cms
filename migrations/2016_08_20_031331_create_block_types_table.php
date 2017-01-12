<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlockTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('block_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->text('description', 65535)->nullable();
            $table->string('identifier', 50);
            $table->tinyInteger('is_shared')->unsigned()->nullable()->default(0);
            $table->integer('application_id')->unsigned()->nullable()->index('fk_page_types_applications1_idx');
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
        Schema::drop('block_types');
    }

}
