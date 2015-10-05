<?php namespace Ao\Data\Models;

use Ao\Data\Models\Categories;
/**
* Basic data model
*/
class Calendar extends \Eloquent
{
	
	protected $table = "tprl_school_calendar";

	protected $fillable = ["title", "slug", "description", "image", "category_id", "schedule_starts", "schedule_ends"];

	public function activities()
	{
		return $this->morphMany("Ao\Data\Models\Activities", "activityable");
	}

	public function category()
	{
		return $this->belongsto("Ao\Data\Models\Categories", "category_id");
	}

	public function categories()
	{
		return Categories::orderBy("created_at", "asc")->where("type", "calendar")->get();
	}

}
	
