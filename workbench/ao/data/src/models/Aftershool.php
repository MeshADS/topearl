<?php namespace Ao\Data\Models;

/**
* Basic data model
*/
class Afterschool extends \Eloquent
{
	
	protected $table = "tprl_afterschool";

	protected $fillable = ["childs_name", "childs_surname", "childs_sex", "dob",
							"address", "starting_on", "parents_name", "parents_occupation", 
							"work_address", "parents_phone", "parents_email", "clubs",];
}
	
