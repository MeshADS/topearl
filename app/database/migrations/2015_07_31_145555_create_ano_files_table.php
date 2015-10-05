<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAnoFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tprl_files', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->text('url');
			$table->integer('type_id');
			$table->text('thumbnail')->nullable();
			$table->text('info')->nullable();
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
		Schema::drop('tprl_files');
	}

}
