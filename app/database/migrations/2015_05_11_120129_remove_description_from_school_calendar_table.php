<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RemoveDescriptionFromSchoolCalendarTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tprl_school_calendar', function(Blueprint $table)
		{
			$table->dropColumn('description');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tprl_school_calendar', function(Blueprint $table)
		{
			$table->text('description');
		});
	}

}
