<?php namespace Ao\Data\Models;

class Awards extends \Eloquent {

	protected $table = "tprl_awards";

	protected $fillable = ["program_id", "user_id", "file", "title", "year"];

	public function user()
	{
	 	return $this->belongsTo("Ao\Data\Models\User", "user_id");
	}

	public function program()
	{
	 	return $this->belongsTo("Ao\Data\Models\Programs", "program_id");
	} 
}