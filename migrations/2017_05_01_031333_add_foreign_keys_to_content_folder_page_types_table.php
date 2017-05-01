<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToContentFolderPageTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('content_folder_page_types', function (Blueprint $table) {
            $table->foreign('folder_type_id', 'FK_folder_type_content_folder_page_types')->references('id')->on('content_types')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('page_type_id', 'FK_page_type_content_folder_page_types')->references('id')->on('content_types')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content_folder_page_types', function (Blueprint $table) {
            $table->dropForeign('FK_folder_type_content_folder_page_types');
            $table->dropForeign('FK_page_type_content_folder_page_types');
        });
    }
}
