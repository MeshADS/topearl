<?php namespace Ao\Data\Models;

class Messages extends \Eloquent {

	protected $table = "tprl_messages";

	protected $fillable = ["user_id", "sender_id", "body"];

	public function recipents()
	{
		return $this->belongsToMany("Ao\Data\Models\User", "tprl_message_user", "message_id", "user_id");
	}

	public function sender()
	{
		return $this->belongsTo("Ao\Data\Models\User", "sender_id");
	}
}