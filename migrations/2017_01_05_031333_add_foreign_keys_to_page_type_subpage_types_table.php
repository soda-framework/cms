<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToPageTypeSubpageTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page_type_subpage_types', function (Blueprint $table) {
            $table->foreign('page_type_id', 'FK_page_type_subpage_types_subpage_types')->references('id')->on('page_types')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('subpage_type_id', 'FK_page_type_subpage_types_page_types')->references('id')->on('page_types')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('page_type_subpage_types', function (Blueprint $table) {
            $table->dropForeign('FK_page_type_subpage_types_page_types');
            $table->dropForeign('FK_page_type_subpage_types_subpage_types');
        });
    }
}
