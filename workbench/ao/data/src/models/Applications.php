<?php namespace Ao\Data\Models;

/**
* Basic data model
*/
class Applications extends \Eloquent
{
	
	protected $table = "tprl_applications";

	protected $fillable = ["childs_name", "childs_surname", "childs_nickname", "childs_age", "childs_sex", "childs_birthday",
							"address", "current_school", "current_class", "previous_schools", "starting_on", "mothers_name",
							"mothers_occupation", "mothers_homephone", "mothers_workphone",	"mothers_mobilephone",
							"mothers_email", "fathers_name", "fathers_occupation", "fathers_homephone",	"fathers_workphone",
							"fathers_mobilephone", "fathers_email"];
}
	
