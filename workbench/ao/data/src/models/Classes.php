<?php namespace Ao\Data\Models;

/**
* Basic data model
*/
class Classes extends \Eloquent
{
	
	protected $table = "tprl_classes";

	protected $fillable = ["name", "slug"];

	public function admission()
	{
		return $this->hasMany("Ao\Data\Models\Admission", "class_id");
	}

}
	
