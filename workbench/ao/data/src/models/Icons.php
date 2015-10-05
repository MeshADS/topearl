<?php namespace Ao\Data\Models;

/**
* Basic data model
*/
class Icons extends \Eloquent
{
	
	protected $table = "tprl_icons";

	protected $fillable = ["name", "slug", "code", "type", "color"];

	public function contacts()
	{
		return $this->hasMany("Ao\Data\Models\Contactdata", "icon_id");
	}

}
	
