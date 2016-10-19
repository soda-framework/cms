<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToApplicationPluginsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_plugins', function (Blueprint $table) {
            $table->foreign('application_id', 'FK_application_plugins_applications')->references('id')->on('applications')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('plugin_id', 'FK_application_plugins_plugins')->references('id')->on('plugins')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_plugins', function (Blueprint $table) {
            $table->dropForeign('FK_application_plugins_applications');
            $table->dropForeign('FK_application_plugins_plugins');
        });
    }

}
