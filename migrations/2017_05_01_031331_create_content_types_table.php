<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description', 65535)->nullable();
            $table->string('identifier', 50);
            $table->integer('application_id')->unsigned()->nullable()->index('fk_content_types_applications1_idx');
            $table->string('view_action')->nullable();
            $table->string('view_action_type')->nullable();
            $table->string('edit_action')->nullable();
            $table->string('edit_action_type')->nullable();
            $table->tinyInteger('is_folder')->unsigned()->nullable()->default(0);
            $table->tinyInteger('is_creatable')->unsigned()->nullable()->default(1);
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
        Schema::drop('content_types');
    }
}
