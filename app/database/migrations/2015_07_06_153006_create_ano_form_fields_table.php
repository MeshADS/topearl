<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAnoFormFieldsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
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


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tprl_form_fields');
	}

}
