<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApplicationUrlsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_urls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('domain');
            $table->integer('application_id')->unsigned()->index('fk_application_url_applications1_idx');
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
        Schema::drop('application_urls');
    }

}
