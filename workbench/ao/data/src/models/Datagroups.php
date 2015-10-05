<?php namespace Ao\Data\Models;

/**
* Datagroups model
*/
class Datagroups extends \Eloquent
{
	
	protected $table = "tprl_datagroups";

	protected $fillable = ["name", "slug", "type"];

	public function headers()
	{
		return $this->hasMany("Ao\Data\Models\Headers", "page_id");
	}

	public function header()
	{
		return $this->hasOne("Ao\Data\Models\Headers", "page_id");
	}

	public function image()
	{
		return $this->hasOne("Ao\Data\Models\Images", "group_id");
	}

	public function images()
	{
		return $this->hasMany("Ao\Data\Models\Images", "group_id");
	}

	public function content()
	{
		return $this->hasMany("Ao\Data\Models\Contentdata", "page_id");
	}

	public function files()
	{
		return $this->hasMany("Ao\Data\Models\Files", "type_id");
	}

}
	
