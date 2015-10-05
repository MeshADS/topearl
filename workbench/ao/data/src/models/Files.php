<?php namespace Ao\Data\Models;

/**
* File manager model
*/
class Files extends \Eloquent
{
	
	protected $table = "tprl_files";

	protected $fillable = ["name", "url", "type_id", "thumbnail", "info", "downloadable", "downloadkey"];

	// public function tags()
	// {
	// 	return $this->morphToMany("Ao\Data\Models\Tag", "tagable");
	// }

	public function type()
	{
		return $this->belongsTo("Ao\Data\Models\Datagroups", "type_id");
	}
}