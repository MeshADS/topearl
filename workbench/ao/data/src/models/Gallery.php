<?php namespace Ao\Data\Models;

use Ao\Data\Models\Categories;
/**
* Basic data model
*/
class Gallery extends \Eloquent
{
	
	protected $table = "tprl_gallery";

	protected $fillable = ["title", "slug", "description", "category_id"];

	public function activities()
	{
		return $this->morphMany("Ao\Data\Models\Activities", "activityable");
	}

	public function category()
	{
		return $this->belongsto("Ao\Data\Models\Categories", "category_id");
	}

	public function photos()
	{
		return $this->hasMany("Ao\Data\Models\Photos", "gallery_id");
	}

	public function photo()
	{
		return $this->hasOne("Ao\Data\Models\Photos", "gallery_id");
	}

	public function categories()
	{
		return Categories::orderBy("created_at", "asc")->where("type", "gallery")->get();
	}

}
	
