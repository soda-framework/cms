<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
            $table->text('description', 65535)->nullable();
            $table->string('slug');
            $table->integer('parent_id')->unsigned()->nullable()->index('pages_parent_id_foreign');
            $table->string('position')->nullable()->default('');
            $table->integer('real_depth')->unsigned();
            $table->integer('application_id')->unsigned()->nullable()->index('FK_pages_applications');
            $table->integer('page_type_id')->unsigned()->nullable()->index('FK_pages_page_types');
            $table->integer('status')->unsigned()->nullable();
            $table->string('package');
            $table->string('action');
            $table->string('action_type');
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
