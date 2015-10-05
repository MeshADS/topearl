<?php namespace Ao\Data\Models;

use Ao\Data\Models\Datagroups;

class Programs extends \Eloquent {

	protected $table = "tprl_programs";

	protected $fillable = ["name", "type_id", "image", "description", "position"];

	public function users()
	{
		return $this->belongsToMany("Ao\Data\Models\User", "tprl_program_user", "program_id", "user_id");
	}

	public function awards()
	{
		return $this->hasMany("Ao\Data\Models\Awards", "program_id");
	}

	public function type()
	{
		return $this->belongsTo("Ao\Data\Models\Datagroups", "type_id");
	}

	public function types()
	{
		// Get types
		$types = Datagroups::where("type", "program")->get();
		// Create types array container
		$typesArr = [];
		// Loop through types
		foreach($types as $type){
			$typesArr[$type->id] = $type->name;
		}
		// return data
		return $typesArr;
	}
}