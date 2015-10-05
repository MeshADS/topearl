<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class DeleteSocialMediaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('tprl_social_media');
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::create('tprl_social_media', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('icon_id');
			$table->string('name');
			$table->string('handle');
			$table->text('url');
			$table->timestamps();
		});
	}

}
