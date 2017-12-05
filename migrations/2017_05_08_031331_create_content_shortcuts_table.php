<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentShortcutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_shortcuts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('text');
            $table->tinyInteger('is_folder')->unsigned()->nullable()->default(0);
            $table->tinyInteger('override_default')->unsigned()->nullable()->default(0);
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('application_id')->unsigned()->nullable();
            $table->integer('content_type_id')->unsigned()->nullable();
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
        Schema::drop('content_shortcuts');
    }
}
