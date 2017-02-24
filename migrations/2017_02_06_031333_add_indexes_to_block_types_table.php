<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToBlockTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('block_types', function(Blueprint $table) {
            $table->index(['id', 'application_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('block_types', function(Blueprint $table) {
            $table->dropIndex(['id', 'application_id']);
        });
    }
}
