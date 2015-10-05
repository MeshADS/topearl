<?php namespace Ao\Data\Models;

/**
* Basic data model
*/
class Comments extends \Eloquent
{
	
	protected $table = "tprl_comments";

	protected $fillable = ["email", "name", "message", "post_id", "publish", "type"];

	public function post()
	{
		return $this->belongsTo("Ao\Data\Models\Gallery", "post_id");
	}

}
	
