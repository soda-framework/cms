<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderableColumnToContentTypeBlockTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('content_type_block_types', function (Blueprint $table) {
            $table->boolean('is_orderable')->default(1)->nullable()->after('max_blocks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content_type_block_types', function (Blueprint $table) {
            $table->dropColumn('is_orderable');
        });
    }
}
