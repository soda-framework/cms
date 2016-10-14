<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlockTypesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('block_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description', 65535)->nullable();
            $table->integer('application_id')->unsigned()->nullable()->index('fk_page_types_applications1_idx');
            $table->string('identifier');
            $table->integer('status')->unsigned()->nullable();
            $table->string('package');
            $table->string('action');
            $table->string('action_type');
            $table->string('edit_action');
            $table->string('edit_action_type')->default(soda_cms_view_path('page-block.index'));
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
        Schema::drop('block_types');
    }

}
