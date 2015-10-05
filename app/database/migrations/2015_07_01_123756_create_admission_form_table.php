<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdmissionFormTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tprl_applications', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('childs_name');
			$table->string('childs_surname');
			$table->string('childs_nickname');
			$table->integer('childs_age');
			$table->string('childs_sex');
			$table->date('childs_birthday');
			$table->text('address');
			$table->string('current_school');
			$table->string('current_class');
			$table->text('previous_schools');
			$table->date('starting_on');
			$table->string('mothers_name');
			$table->string('mothers_occupation');
			$table->string('mothers_homephone');
			$table->string('mothers_workphone');
			$table->string('mothers_mobilephone');
			$table->string('mothers_email');
			$table->string('fathers_name');
			$table->string('fathers_occupation');
			$table->string('fathers_homephone');
			$table->string('fathers_workphone');
			$table->string('fathers_mobilephone');
			$table->string('fathers_email');
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
		Schema::drop('tprl_applications');
	}

}
