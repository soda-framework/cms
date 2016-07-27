<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFieldsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fields', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('field_type')->nullable();
			$table->text('field_params', 65535)->nullable();
			$table->text('value', 65535)->nullable();
			$table->string('name')->nullable();
			$table->string('field_name')->nullable();
			$table->integer('application_user_id')->index('fk_fields_application_users1_idx');
			$table->text('description', 65535)->nullable();
			$table->integer('application_id')->index('fk_fields_applications1_idx');
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
		Schema::drop('fields');
	}

}
