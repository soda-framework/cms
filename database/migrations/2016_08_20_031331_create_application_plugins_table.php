<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApplicationPluginsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('application_plugins', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('plugin_id')->unsigned()->index('FK_application_plugins_plugins');
			$table->integer('application_id')->unsigned()->index('FK_application_plugins_applications');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('application_plugins');
	}

}
