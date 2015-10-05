<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddRulesToAnoFormElementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tprl_form_elements', function(Blueprint $table)
		{
			$table->text('rules');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tprl_form_elements', function(Blueprint $table)
		{
			$table->dropColumn('rules');
		});
	}

}
