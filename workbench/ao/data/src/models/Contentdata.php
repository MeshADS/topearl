<?php namespace Ao\Data\Models;

/**
* Basic data model
*/
class Contentdata extends \Eloquent
{
	
	protected $table = "tprl_arbitrary_data";

	protected $fillable = ["title", "slug", "page_id", "body"];

	public function page()
	{
		return $this->belongsTo("Ao\Data\Models\Datagroups", "page_id");
	}

}
	
