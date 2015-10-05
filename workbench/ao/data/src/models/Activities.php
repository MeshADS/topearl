<?php namespace Ao\Data\Models;

/**
* Basic data model
*/
class Activities extends \Eloquent
{
	
	protected $table = "tprl_activities";

	protected $fillable = ["activityable_id", "activityable_type"];

	public function activityable()
	{
		return $this->morphTo();
	}

}
	
