<?php namespace Ao\Data\Models;

use Ao\Data\Models\Contactdata;
use Ao\Data\Models\Classes;
/**
* Basic data model
*/
class Admission extends \Eloquent
{
	
	protected $table = "tprl_admission";

	protected $fillable = ["class_id", "close_date", "open_date", "title", "description", "contact1", "contact2", "image", "thumbnail"];

	public function aclass()
	{
		return $this->belongsTo("Ao\Data\Models\Classes", "class_id");
	}

	public function contactdata1()
	{
		return $this->belongsTo("Ao\Data\Models\Contactdata", "contact1");
	}

	public function contactdata2()
	{
		return $this->belongsTo("Ao\Data\Models\Contactdata", "contact2");
	}

	public function classes()
	{
		return Classes::orderBy("name", "asc")->get();
	}

	public function contacts()
	{
		return Contactdata::orderBy("name", "asc")->get()->groupBy("type");
	}

}
	
