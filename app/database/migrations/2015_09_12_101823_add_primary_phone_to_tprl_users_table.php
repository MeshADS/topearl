<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPrimaryPhoneToTprlUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tprl_users', function(Blueprint $table)
		{
			$table->integer('phone_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tprl_users', function(Blueprint $table)
		{
			$table->dropColumn('phone_id');
		});
	}

}
