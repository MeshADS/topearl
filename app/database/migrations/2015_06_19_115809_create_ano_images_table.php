<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAnoImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tprl_images', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('group');
			$table->text('image');
			$table->text('caption');
			$table->text('link_url');
			$table->integer('link_type');
			$table->string('link_title');
			$table->integer('order');
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
		Schema::drop('tprl_images');
	}

}
