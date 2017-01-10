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
            $table->string('slug')->default('');
            $table->integer('parent_id')->unsigned()->nullable()->index('pages_parent_id_foreign');
            $table->integer('position')->unsigned()->nullable()->default('0');
            $table->integer('real_depth')->unsigned();
            $table->integer('allowed_children', 1)->unsigned()->nullable()->default(1);
            $table->integer('can_delete', 1)->unsigned()->nullable()->default(1);
            $table->integer('application_id')->unsigned()->nullable()->index('FK_pages_applications');
            $table->integer('page_type_id')->unsigned()->nullable()->index('FK_pages_page_types');
            $table->integer('status', 1)->unsigned()->nullable()->default(0);
            $table->string('view_action')->nullable();
            $table->string('view_action_type');
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
