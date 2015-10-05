<?php namespace Ao\Data\Models;

/**
* Basic data model
*/
class Photos extends \Eloquent
{
	
	protected $table = "tprl_photos";

	protected $fillable = ["thumbnail", "image", "caption", "gallery_id"];

	public function gallery()
	{
		return $this->belongsTo("Ao\Data\Models\Gallery", "gallery_id");
	}

}
	
