<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTprlAwardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tprl_awards', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->integer('program_id');
			$table->integer('user_id');
			$table->text('file');
			$table->string('year');
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
		Schema::drop('tprl_awards');
	}

}
