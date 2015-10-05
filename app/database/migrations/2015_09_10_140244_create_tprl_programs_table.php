<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTprlProgramsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tprl_programs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->integer('type_id');
			$table->text('image');
			$table->text('description');
			$table->integer('position');
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
		Schema::drop('tprl_programs');
	}

}
