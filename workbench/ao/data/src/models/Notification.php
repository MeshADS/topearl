<?php namespace Ao\Data\Models;

/**
* Basic data model
*/
class Notification extends \Eloquent
{
	
	protected $table = "tprl_notification";

	protected $fillable = ["type_id", "url", "user_id", "group_id", "read"];

	public function type()
	{
		return $this->belongsTo("Ao\Data\Models\Notificationtype", "type_id");
	}

}
	
