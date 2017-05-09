<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuicklinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quicklinks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('text');
            $table->string('route_name');
            $table->text('route_params');
            $table->text('request_params');
            $table->integer('user_id')->unsigned()->nullable()->index('FK_quicklinks_users');
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
        Schema::drop('quicklinks');
    }
}
