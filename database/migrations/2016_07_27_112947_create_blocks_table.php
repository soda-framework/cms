<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBlocksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('blocks', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 45)->nullable();
			$table->string('identifier')->nullable();
			$table->timestamps();
			$table->integer('application_id')->nullable();
			$table->integer('block_type_id')->nullable();
			$table->text('description', 65535)->nullable();
			$table->integer('application_user_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('blocks');
	}

}
