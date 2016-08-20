<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMediaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('media', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('related_id')->unsigned();
			$table->string('related_table');
			$table->string('related_field');
			$table->integer('position')->unsigned();
			$table->text('media', 65535);
			$table->enum('media_type', array('image','video','other'));
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
		Schema::drop('media');
	}

}
