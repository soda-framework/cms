<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fieldables', function(Blueprint $table) {
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
