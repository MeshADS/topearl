<?php namespace Ao\Data\Models;

/**
* Basic data model
*/
class Basicdata extends \Eloquent
{
	
	protected $table = "tprl_basic_data";

	protected $fillable = ["shortname", "fullname", "logo", "logo_2x", "logo_sm", "logo_white", "logo_white_2x", "logo_white_sm", "description"];

}
	
