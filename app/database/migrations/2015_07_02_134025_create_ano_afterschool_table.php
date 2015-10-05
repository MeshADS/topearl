<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAnoAfterschoolTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tprl_afterschool', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('childs_name');
			$table->string('childs_surname');
			$table->date('dob');
			$table->string('childs_sex');
			$table->text('address');
			$table->string('parents_name');
			$table->string('parents_occupation');
			$table->text('work_address');
			$table->string('parents_phone');
			$table->string('parents_email');
			$table->text('clubs');
			$table->date('starting_on');
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
		Schema::drop('tprl_afterschool');
	}

}
