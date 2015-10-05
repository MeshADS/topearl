<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddTypeToAnoFormElementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tprl_form_elements', function(Blueprint $table)
		{
			$table->string('type');
			$table->string('slug');
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
			$table->dropColumn('type');
			$table->dropColumn('slug');
		});
	}

}
