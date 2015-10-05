<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tprl_comments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('post_id');
			$table->text('message');
			$table->string('name');
			$table->string('email')->nullable();
			$table->integer('show');
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
		Schema::drop('tprl_comments');
	}

}
