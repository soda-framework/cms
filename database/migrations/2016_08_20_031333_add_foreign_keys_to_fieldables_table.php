<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToFieldablesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('fieldables', function(Blueprint $table)
		{
			$table->foreign('field_id', 'FK_fieldables_fields')->references('id')->on('fields')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('fieldables', function(Blueprint $table)
		{
			$table->dropForeign('FK_fieldables_fields');
		});
	}

}
