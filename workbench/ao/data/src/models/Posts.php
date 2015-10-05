<?php namespace Ao\Data\Models;

/**
* Basic data model
*/
class Posts extends \Eloquent
{
	
	protected $table = "tprl_posts";

	protected $fillable = ["title", "slug", "image", "thumbnail", "category_id", "caption", "publish_state", "body"];

	public function category()
	{
		return $this->belongsto("Ao\Data\Models\Categories", "category_id");
	}

	public function comments()
	{
		return $this->hasMany("Ao\Data\Models\Comments", "post_id");
	}

	public function comment()
	{
		return $this->hasOne("Ao\Data\Models\Comments", "post_id");
	}

	public function categories()
	{
		return Categories::orderBy("created_at", "asc")->where("type", "posts")->get();
	}

}
	
