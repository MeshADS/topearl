<?php namespace Ao\Data\Models;

/**
* Basic data model
*/
class Groups extends \Eloquent
{
	
	protected $table = "tprl_groups";

	public function users()
	{
		return $this->belongsToMany("Ao\Data\Models\User", "tprl_users_groups", "group_id", "user_id");
	}

}
	
