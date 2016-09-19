<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPagesClosureTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages_closure', function (Blueprint $table) {
            $table->foreign('ancestor', 'FK_pages_closure_pages')->references('id')->on('pages')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('descendant', 'FK_pages_closure_pages_2')->references('id')->on('pages')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages_closure', function (Blueprint $table) {
            $table->dropForeign('FK_pages_closure_pages');
            $table->dropForeign('FK_pages_closure_pages_2');
        });
    }

}
