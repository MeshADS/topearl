<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddTypeToAdmissionRegistrationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tprl_admission_registration', function(Blueprint $table)
		{
			$table->integer('type');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tprl_admission_registration', function(Blueprint $table)
		{
			$table->dropColumn('type');
		});
	}

}
