<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePageTypesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description', 65535)->nullable();
            $table->string('identifier', 50);
            $table->integer('application_id')->unsigned()->index('fk_page_types_applications1_idx');
            $table->string('view_action')->nullable();
            $table->string('view_action_type')->nullable();
            $table->string('edit_action')->nullable();
            $table->string('edit_action_type')->nullable();
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
        Schema::drop('page_types');
    }

}
