<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlockTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('block_types', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name')->nullable();
			$table->text('description', 65535)->nullable();
			$table->integer('application_users_id')->index('fk_page_types_application_users1_idx');
			$table->integer('application_id')->index('fk_page_types_applications1_idx');
			$table->string('action')->nullable();
			$table->string('action_type')->nullable();
			$table->string('package')->nullable();
			$table->timestamps();
			$table->string('identifier')->nullable();
			$table->integer('status_id')->nullable();
			$table->string('edit_action')->nullable();
			$table->string('edit_action_type')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('block_types');
	}

}
