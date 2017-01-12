<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFieldablesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fieldables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('field_id')->unsigned()->index('FK_fieldables_fields');
            $table->integer('fieldable_id')->unsigned();
            $table->string('fieldable_type');
            $table->tinyInteger('show_in_table')->unsigned()->nullable()->default(1);
            $table->integer('position')->unsigned()->nullable()->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fieldables');
    }

}
