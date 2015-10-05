<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddMobileImageTopAnoHeadersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tprl_headers', function(Blueprint $table)
		{
			$table->text('mobile_image');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tprl_headers', function(Blueprint $table)
		{
			$table->dropColumn('mobile_image');
		});
	}

}
