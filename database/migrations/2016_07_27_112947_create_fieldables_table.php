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
        Schema::create('fieldables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('field_id')->nullable();
            $table->integer('fieldable_id')->nullable();
            $table->string('fieldable_type')->nullable();
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
