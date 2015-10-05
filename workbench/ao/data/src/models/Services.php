<?php namespace Ao\Data\Models;

/**
* Basic data model
*/
class Services extends \Eloquent
{
	
	protected $table = "tprl_services";

	protected $fillable = ["privider", "data"];

}
	
