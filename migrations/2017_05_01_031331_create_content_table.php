<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('identifier', 50)->nullable();
            $table->tinyInteger('status')->unsigned()->nullable()->default(0);
            $table->integer('parent_id')->unsigned()->nullable()->index('content_parent_id_foreign');
            $table->integer('position')->unsigned()->nullable()->default('0');
            $table->integer('real_depth')->unsigned();
            $table->integer('application_id')->unsigned()->nullable()->index('FK_content_applications');
            $table->integer('content_type_id')->unsigned()->nullable()->index('FK_content_content_types');
            $table->string('view_action')->nullable();
            $table->string('view_action_type');
            $table->string('edit_action')->nullable();
            $table->string('edit_action_type')->nullable();
            $table->tinyInteger('is_sluggable')->unsigned()->nullable()->default(1);
            $table->tinyInteger('is_folder')->unsigned()->nullable()->default(0);
            $table->tinyInteger('is_movable')->unsigned()->nullable()->default(1);
            $table->tinyInteger('is_deletable')->unsigned()->nullable()->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('content');
    }
}
