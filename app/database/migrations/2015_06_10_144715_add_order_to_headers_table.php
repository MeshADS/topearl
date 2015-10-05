<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddOrderToHeadersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tprl_headers', function(Blueprint $table)
		{
			$table->integer('order');
			$table->string('link_title');
			$table->text('link_url');
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
			$table->dropColumn('order');
			$table->dropColumn('link_title');
			$table->dropColumn('link_url');
		});
	}

}
