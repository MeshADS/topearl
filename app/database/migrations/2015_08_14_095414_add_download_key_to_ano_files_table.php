<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddDownloadKeyToAnoFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tprl_files', function(Blueprint $table)
		{
			$table->string('downloadkey')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tprl_files', function(Blueprint $table)
		{
			$table->dropColumn('downloadkey');
		});
	}

}
