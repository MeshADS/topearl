<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tprl_posts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('category_id');
			$table->string('caption');
			$table->text('thumbnail');
			$table->text('image');
			$table->integer('publish_state');
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
		Schema::drop('tprl_posts');
	}

}
