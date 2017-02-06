<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddIndexesToPageTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page_types', function (Blueprint $table) {
            $table->index(['page_id', 'status', 'application_id']);
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
            $table->dropIndex(['page_id', 'status', 'application_id']);
        });
    }
}
