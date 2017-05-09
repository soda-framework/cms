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
            $table->integer('parent_id')->unsigned()->nullable()->index('content_shortcuts_parent_id_foreign');
            $table->integer('application_id')->unsigned()->nullable()->index('FK_content_shortcuts_applications');
            $table->integer('content_type_id')->unsigned()->nullable()->index('FK_content_shortcuts_content_types');
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
