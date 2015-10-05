<?php namespace Ao\Data\Models;

class Phonenumbers extends \Eloquent {

	protected $table = "tprl_phonenumbers";

	protected $fillable = ["name", "number", "user_id"];

	public function user()
	{
		return $this->belongsTo("Ao\Data\Models\User", "user_id");
	}
}