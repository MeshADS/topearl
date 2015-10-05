<?php namespace Ao\Data\Models;

/**
* Submission model
*/
class Elements extends \Eloquent
{
	
	protected $table = "tprl_form_elements";

	protected $fillable = ["form_id", "position", "name", "rules", "type", "groupie", "size", "slug"];

	public function form()
	{
		return $this->belongsTo("Ao\Data\Models\Forms", "form_id");
	}

	public function listValues()
	{
		return $this->hasMany("Ao\Data\Models\Elementslistvalues", "element_id");
	}
}