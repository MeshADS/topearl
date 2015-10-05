<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBasicDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tprl_basic_data', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('shortname');
			$table->string('fullname');
			$table->text('logo');
			$table->text('logo_2x');
			$table->text('logo_sm');
			$table->text('logo_white');
			$table->text('logo_white_2x');
			$table->text('logo_white_sm');
			$table->text('description');
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
		Schema::drop('tprl_basic_data');
	}

}
