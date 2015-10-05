<?php namespace Ao\Data\Models;

/**
* Submission model
*/
class Elementslistvalues extends \Eloquent
{
	
	protected $table = "tprl_elements_list_values";

	protected $fillable = ["element_id", "name", "slug", "value"];

	public function element()
	{
		return $this->belongsTo("Ao\Data\Models\Elements", "element_id");
	}
}