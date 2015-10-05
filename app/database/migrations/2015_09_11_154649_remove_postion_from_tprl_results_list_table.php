<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RemovePostionFromTprlResultsListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tprl_results_list', function(Blueprint $table)
		{
			$table->dropColumn('postion');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tprl_results_list', function(Blueprint $table)
		{
			$table->integer('postion');
		});
	}

}
