<?php namespace Ao\Data\Models;

class Resultslist extends \Eloquent {

	protected $table = "tprl_results_list";

	protected $fillable = ["name", "value", "position", "result_id"];

	public function result()
	{
		return $this->belongsTo("Ao\Data\Models\Results", "result_id");
	}
}