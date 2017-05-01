<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentFolderPageTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_folder_page_types', function (Blueprint $table) {
            $table->integer('folder_type_id')->unsigned()->index('FK_folder_type_content_folder_page_types')->nullable();
            $table->integer('page_type_id')->unsigned()->index('FK_page_type_content_folder_page_types')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('content_folder_page_types');
    }
}
