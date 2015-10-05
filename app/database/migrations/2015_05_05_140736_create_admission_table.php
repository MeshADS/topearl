<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdmissionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tprl_admission', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('class_id');
			$table->datetime('close_date');
			$table->string('title');
			$table->text('description');
			$table->integer('contact1');
			$table->integer('contact2')->nullbale();
			$table->text('image');
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
		Schema::drop('tprl_admission');
	}

}
