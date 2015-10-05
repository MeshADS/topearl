<?php namespace Ao\Data\Models;
/**
* Basic data model
*/
class Images extends \Eloquent
{
	
	protected $table = "tprl_images";

	protected $fillable = ["group_id", "title", "image", "caption", "link_url", "link_type", "link_title", "link_color", "order"];

	public function group()
	{
		return $this->belongsTo("Ao\Data\Models\Datagroups", "group_id");
	}

}
	
