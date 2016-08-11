<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     * Fields for the media table
     * @return void
     */
    public function up()
    {
        //The schema for the media table
        Schema::create('media', function(Blueprint $table){
            $table->increments('id');
            $table->integer('related_id');
            $table->string('related_table');
            $table->string('related_field');
            $table->integer('position');
            $table->text('media');      //This is a JSON column however leaving it as text for older MYSQL versions
            $table->enum('media_type', ['image', 'video', 'other']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Drops the media table
        Scheme::drop('media');
    }
}
