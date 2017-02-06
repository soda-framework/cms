<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->default('');
            $table->integer('parent_id')->unsigned()->nullable()->index('FK_pages_pages');
            $table->integer('position')->unsigned()->nullable()->default('0');
            $table->integer('real_depth')->unsigned();
            $table->integer('application_id')->unsigned()->nullable()->index('FK_pages_applications');
            $table->integer('page_type_id')->unsigned()->nullable()->index('FK_pages_page_types');
            $table->integer('status')->unsigned()->nullable();
            $table->string('package')->nullable();
            $table->string('action')->default('');
            $table->string('action_type')->default('view');
            $table->string('edit_action')->nullable();
            $table->string('edit_action_type')->nullable();
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
        Schema::drop('pages');
    }
}
