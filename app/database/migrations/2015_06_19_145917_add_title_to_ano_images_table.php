<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddTitleToAnoImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tprl_images', function(Blueprint $table)
		{
			$table->string('title');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tprl_images', function(Blueprint $table)
		{
			$table->dropColumn('title');
		});
	}

}
