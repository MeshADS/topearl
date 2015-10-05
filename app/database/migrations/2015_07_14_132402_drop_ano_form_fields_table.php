<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class DropAnoFormFieldsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('tprl_form_fields');
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::create('tprl_form_fields', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type');
			$table->text('attr');
			$table->string('name');
			$table->string('label');
			$table->string('value');
			$table->string('fieldset');
			$table->text('validation_rules');
			$table->integer('position');
			$table->integer('form_id');
			$table->timestamps();
		});
	}

}
