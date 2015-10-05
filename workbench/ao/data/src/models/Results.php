<?php namespace Ao\Data\Models;

use Ao\Data\Models\Datagroups;

class Results extends \Eloquent {
	
	protected $table = "tprl_results";

	protected $fillable = ["program_id", "year", "semester_id", "user_id"];

	public function resultslist()
	{
		return $this->hasMany("Ao\Data\Models\Resultslist", "result_id");
	}

	public function user()
	{
		return $this->belongsTo("Ao\Data\Models\User", "user_id");
	}

	public function program()
	{
		return $this->belongsTo("Ao\Data\Models\Programs", "program_id");
	}

	public function semester()
	{
		return $this->belongsTo("Ao\Data\Models\Datagroups", "semester_id");
	}
}