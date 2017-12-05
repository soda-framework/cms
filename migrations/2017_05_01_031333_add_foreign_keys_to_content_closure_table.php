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
            $table->foreign('ancestor')->references('id')->on('content')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('descendant')->references('id')->on('content')->onUpdate('cascade')->onDelete('cascade');
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
            $table->dropForeign(['ancestor']);
            $table->dropForeign(['descendant']);
        });
    }
}
