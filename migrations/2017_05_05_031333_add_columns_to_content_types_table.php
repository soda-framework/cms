<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToContentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('content_types', function (Blueprint $table) {
            $table->boolean('is_sluggable')->default(1)->nullable()->after('is_folder');
            $table->boolean('is_publishable')->default(1)->nullable()->after('is_sluggable');
            $table->boolean('is_movable')->default(1)->nullable()->after('is_publishable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content_types', function (Blueprint $table) {
            $table->dropColumn(['is_sluggable', 'is_publishable', 'is_movable']);
        });
    }
}
