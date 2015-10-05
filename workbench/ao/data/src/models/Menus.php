<?php namespace Ao\Data\Models;

/**
* Menu builder model
*/
class Menus extends \Eloquent
{
	
	protected $table = "tprl_menus";

	protected $fillable = ["title", "slug", "url", "isslave", "ext", "master_id", "color", "position"];

	public function submenus()
	{
		return $this->hasMany("Ao\Data\Models\Menus", "master_id");
	}
}