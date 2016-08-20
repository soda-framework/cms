<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToFieldsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('fields', function(Blueprint $table)
		{
			$table->foreign('application_id', 'FK_fields_applications')->references('id')->on('applications')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('fields', function(Blueprint $table)
		{
			$table->dropForeign('FK_fields_applications');
		});
	}

}
