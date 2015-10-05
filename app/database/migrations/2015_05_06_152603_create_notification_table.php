<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tprl_notification', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('type_id');
			$table->text('url');
			$table->integer('user_id')->nullable();
			$table->integer('group_id')->nullable();
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
		Schema::drop('tprl_notification');
	}

}
