<?php namespace Ao\Data\Models;

/**
* Basic data model
*/
class Categories extends \Eloquent
{
	
	protected $table = "tprl_categories";

	protected $fillable = ["name", "slug", "type"];

	public function galleries()
	{
		return $this->hasMany("Ao\Data\Models\Gallery", "category_id");
	}

	public function calendar()
	{
		return $this->hasMany("Ao\Data\Models\Schoolcalendar", "category_id");
	}

	public function posts()
	{
		return $this->hasMany("Ao\Data\Models\Posts", "category_id");
	}

	public function post()
	{
		return $this->hasOne("Ao\Data\Models\Posts", "category_id");
	}

}
	
