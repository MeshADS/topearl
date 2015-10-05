<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddThumbnailToAdmissionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tprl_admission', function(Blueprint $table)
		{
			$table->text('thumbnail');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tprl_admission', function(Blueprint $table)
		{
			$table->dropColumn('thumbnail');
		});
	}

}
