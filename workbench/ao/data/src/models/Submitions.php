<?php namespace Ao\Data\Models;

/**
* Submission model
*/
class Submitions extends \Eloquent
{
	
	protected $table = "tprl_form_submitions";

	protected $fillable = ["form_id", "data"];

	public function form()
	{
		return $this->belongsToOne("Ao\Data\Models\Forms", "form_id");
	}
}