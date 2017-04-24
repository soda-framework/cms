<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('logo_url')->nullable()->after('name');
            $table->string('css_url')->nullable()->after('logo_url');
            $table->boolean('append_css')->nullable()->after('css_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('logo_url');
            $table->dropColumn('css_url');
            $table->dropColumn('append_css');
        });
    }
}
