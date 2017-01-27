<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCanCreateToPageTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page_types', function (Blueprint $table) {
            $table->integer('can_create')->unsigned()->nullable()->after('identifier')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('page_types', function (Blueprint $table) {
            $table->dropColumn('can_create');
        });
    }
}
