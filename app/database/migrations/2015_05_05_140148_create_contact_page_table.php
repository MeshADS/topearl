<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContactPageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tprl_contact_page', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('map');
			$table->integer('tel1');
			$table->integer('tel2')->nullable();
			$table->integer('address1');
			$table->integer('address2')->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tprl_contact_page');
	}

}
