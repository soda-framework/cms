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
            $table->foreign('folder_type_id')->references('id')->on('content_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('page_type_id')->references('id')->on('content_types')->onUpdate('cascade')->onDelete('cascade');
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
            $table->dropForeign(['folder_type_id']);
            $table->dropForeign(['page_type_id']);
        });
    }
}
