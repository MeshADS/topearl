<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSchoolCalendarTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tprl_school_calendar', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('category_id');
			$table->text('image')->nullable();
			$table->string('title');
			$table->string('slug');
			$table->text('description');
			$table->datetime('schedule_starts');
			$table->datetime('schedule_ends')->nullable();
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
		Schema::drop('tprl_school_calendar');
	}

}
