<?php namespace Ao\Data\Models;

use Ao\Data\Models\Icons;

/**
* Basic data model
*/
class Headers extends \Eloquent
{
	
	protected $table = "tprl_headers";

	protected $fillable = ["page_id", "title", "image", "mobile_image", "caption", "link_url", "link_type", "link_title", "order"];

	public function page()
	{
		return $this->belongsTo("Ao\Data\Models\Datagroups", "page_id");
	}

}
	
