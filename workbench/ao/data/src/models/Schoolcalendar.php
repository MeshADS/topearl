<?php namespace Ao\Data\Models;

/**
* Basic data model
*/
class Schoolcalendar extends \Eloquent
{
	
	protected $table = "tprl_school_calendar";

	protected $fillable = ["title", "slug", "image", "category_id", "description", "schedule_starts", "schedule_ends"];

	public function category()
	{
		return $this->belongsto("Ao\Data\Models\Categories", "category_id");
	}

}
	
