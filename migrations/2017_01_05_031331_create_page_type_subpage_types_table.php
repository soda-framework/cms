<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageTypeSubpageTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_type_subpage_types', function(Blueprint $table) {
            $table->integer('page_type_id')->unsigned()->index('FK_page_type_subpage_types_page_types')->nullable();
            $table->integer('subpage_type_id')->unsigned()->index('FK_page_type_subpage_types_subpage_types')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('page_type_subpage_types');
    }
}
