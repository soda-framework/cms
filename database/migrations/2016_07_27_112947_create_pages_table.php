<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name')->nullable();
			$table->string('slug')->nullable();
			$table->string('package')->nullable();
			$table->string('action')->nullable();
			$table->string('action_type')->nullable();
			$table->integer('parent_id')->unsigned()->nullable()->index('pages_parent_id_foreign');
			$table->string('position')->nullable()->default('');
			$table->integer('real_depth')->unsigned();
			$table->softDeletes();
			$table->integer('application_user_id')->nullable();
			$table->integer('application_id')->nullable();
			$table->integer('template_id')->nullable();
			$table->integer('page_type_id')->nullable();
			$table->integer('status_id')->nullable();
			$table->string('edit_action')->nullable();
			$table->string('edit_action_type')->nullable();
			$table->text('description', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pages');
	}

}
