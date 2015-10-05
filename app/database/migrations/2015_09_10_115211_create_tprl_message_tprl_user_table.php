<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTprlMessageTprlUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tprl_message_user', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('message_id')->unsigned()->index();
			$table->foreign('message_id')->references('id')->on('tprl_messages')->onDelete('cascade');
			$table->integer('user_id')->unsigned()->index();
			$table->foreign('user_id')->references('id')->on('tprl_users')->onDelete('cascade');
			$table->integer('state');
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
		Schema::drop('tprl_message_user');
	}

}
