<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->foreign('application_id', 'FK_pages_applications')->references('id')->on('applications')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('page_type_id', 'FK_pages_page_types')->references('id')->on('page_types')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreign('parent_id', 'FK_pages_pages')->references('id')->on('pages')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign('FK_pages_applications');
            $table->dropForeign('FK_pages_page_types');
            $table->dropForeign('FK_pages_pages');
        });
    }

}
