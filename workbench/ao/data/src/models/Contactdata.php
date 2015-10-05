<?php namespace Ao\Data\Models;

use Ao\Data\Models\Icons;

/**
* Basic data model
*/
class Contactdata extends \Eloquent
{
	
	protected $table = "tprl_contact_data";

	protected $fillable = ["name", "type", "data", "icon_id", "color"];

	public function icon()
	{
		return $this->belongsTo("Ao\Data\Models\Icons", "icon_id");
	}

	public function allicons()
	{
		return Icons::orderBy("name")->get()->groupBy("type");
	}

}
	
