<?php namespace Ao\Data\Models;
use Ao\Data\Models\Programs;
/**
* Basic data model
*/
class User extends \Eloquent
{
	
	protected $table = "tprl_users";

	protected $hidden = ["password"];

	protected $fillable = ["avatar", "first_name", "last_name", "name", "email", "phone_id"];

	public function groups()
	{
		return $this->belongsToMany("Ao\Data\Models\Groups", "tprl_users_groups", "user_id", "group_id");
	}

	public function programs()
	{
		return $this->belongsToMany("Ao\Data\Models\Programs", "tprl_program_user", "user_id", "program_id");
	}

	public function messages()
	{
		return $this->belongsToMany("Ao\Data\Models\Messages", "tprl_message_user", "user_id", "message_id");
	}

	public function outbox()
	{
		return $this->hasMany("Ao\Data\Models\Messages", "sender_id");
	}

	public function results()
	{
		return $this->hasMany("Ao\Data\Models\Results", "user_id");
	}

	public function awards()
	{
		return $this->hasMany("Ao\Data\Models\Awards", "user_id");
	}

	public function phonenumbers()
	{
		return $this->hasMany("Ao\Data\Models\Phonenumbers", "user_id");
	}

	public function phone()
	{
		return $this->belongsTo("Ao\Data\Models\Phonenumbers", "phone_id");
	}

	public function getPrograms()
	{
		return Programs::orderBy("position", "asc")->get();
	}

	public function getSemesters()
	{
		// Get all semesters
		$semesters = Datagroups::where(["type"=>"semester"])->get();
		// Semester array
		$semestersArray = [];
		// Loop through return semesters
		foreach ($semesters as $semester) {
			// Add item to array
			$semestersArray[$semester->id] = $semester->name;
		}
		// Return array
		return $semestersArray;

	}

}
	
