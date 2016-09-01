<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePageBlocksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('page_blocks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('page_id')->unsigned()->index('FK_page_blocks_pages');
			$table->integer('block_id')->unsigned()->index('FK_page_blocks_blocks');
            $table->integer('can_create')->unsigned();
            $table->integer('can_delete')->unsigned();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('page_blocks');
	}

}
