<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePagesClosureTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pages_closure', function(Blueprint $table)
		{
			$table->increments('closure_id');
			$table->integer('ancestor')->unsigned()->index('pages_closure_ancestor_foreign');
			$table->integer('descendant')->unsigned()->index('pages_closure_descendant_foreign');
			$table->integer('depth')->unsigned();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pages_closure');
	}

}
