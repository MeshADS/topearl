<?php namespace Ao\Data\Models;

/**
* Basic data model
*/
class Forms extends \Eloquent
{
	
	protected $table = "tprl_forms";

	protected $fillable = ["name", "slug", "publish", "notify"];

	public function submitions()
	{
		return $this->hasMany("Ao\Data\Models\Submitions", "form_id");
	}

	public function elements()
	{
		return $this->hasMany("Ao\Data\Models\Elements", "form_id");
	}

}
	
