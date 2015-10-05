<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddMessageToAnoElementsRulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tprl_elements_rules', function(Blueprint $table)
		{
			$table->string('message')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tprl_elements_rules', function(Blueprint $table)
		{
			$table->dropColumn('message');
		});
	}

}
