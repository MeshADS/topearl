<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTprlResultsListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tprl_results_list', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('value');
			$table->integer('postion');
			$table->integer('result_id');
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
		Schema::drop('tprl_results_list');
	}

}
