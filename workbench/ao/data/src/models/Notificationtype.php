<?php namespace Ao\Data\Models;

/**
* Basic data model
*/
class Notificationtype extends \Eloquent
{
	
	protected $table = "tprl_notificationtype";

	protected $fillable = ["name", "description"];

	public function notification()
	{
		return $this->hasMany("Ao\Data\Models\Notification", "type_id");
	}

}
	
