<?php namespace Ao\Data\Models;

/**
* Tag system polymorphic model
*/
class Tag extends \Eloquent
{
	
	protected $table = "tprl_tags";

	protected $fillable = ["name", "slug", "tagable_type", "tagable_id"];

	public function tagable()
	{
		return $this->morphTo();
	}
}