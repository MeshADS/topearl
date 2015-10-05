<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdmissionRegistrationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tprl_admission_registration', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('admission_id');
			$table->string('name');
			$table->string('email');
			$table->string('phone');
			$table->text('message');
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
		Schema::drop('tprl_admission_registration');
	}

}
