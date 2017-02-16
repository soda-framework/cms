<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSodaAboutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soda_about', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->text('text', 65535)->nullable();
            $table->dateTime('date')->nullable();
            $table->integer('display_time')->nullable();
            $table->timestamps();
            $table->integer('page_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('soda_about');
    }
}
