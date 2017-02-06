<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddIndexesToBlockTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('block_types', function (Blueprint $table) {
            $table->index(['id', 'status', 'application_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('block_types', function (Blueprint $table) {
            $table->dropIndex(['id', 'status', 'application_id']);
        });
    }
}
