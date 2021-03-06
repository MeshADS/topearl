<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddIconIdToContactDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tprl_contact_data', function(Blueprint $table)
		{
			$table->integer('icon_id')->foreign();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tprl_contact_data', function(Blueprint $table)
		{
			$table->dropColumn('icon_id');
		});
	}

}
