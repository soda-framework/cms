<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToContentClosureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('content_closure', function (Blueprint $table) {
            $table->foreign('ancestor', 'FK_content_closure_content')->references('id')->on('content')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('descendant', 'FK_content_closure_content_2')->references('id')->on('content')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content_closure', function (Blueprint $table) {
            $table->dropForeign('FK_content_closure_content');
            $table->dropForeign('FK_content_closure_content_2');
        });
    }
}
