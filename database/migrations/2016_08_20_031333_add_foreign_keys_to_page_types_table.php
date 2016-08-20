<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPageTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('page_types', function(Blueprint $table)
		{
			$table->foreign('application_id', 'FK_page_types_applications')->references('id')->on('applications')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('page_types', function(Blueprint $table)
		{
			$table->dropForeign('FK_page_types_applications');
		});
	}

}
