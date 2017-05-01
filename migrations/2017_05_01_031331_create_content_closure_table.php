<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentClosureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_closure', function (Blueprint $table) {
            $table->increments('closure_id');
            $table->integer('ancestor')->unsigned()->index('content_closure_ancestor_foreign');
            $table->integer('descendant')->unsigned()->index('content_closure_descendant_foreign');
            $table->integer('depth')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('content_closure');
    }
}
