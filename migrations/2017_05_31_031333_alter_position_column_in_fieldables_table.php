<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPositionColumnInFieldablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fieldables', function (Blueprint $table) {
            $table->integer('position')->unsigned()->nullable()->default('1')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fieldables', function (Blueprint $table) {
            $table->integer('position')->unsigned()->nullable()->default('0')->change();
        });
    }
}
