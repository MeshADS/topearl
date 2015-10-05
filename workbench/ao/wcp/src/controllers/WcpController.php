<?php namespace Ao\Wcp\Controllers;
use Ao\Data\Models\Datagroups;
class WcpController extends \Controller{

	/*
	* @Var array
	* @Access Level Protected
	*/
	protected $filterExceptions = [];

	/*
	* @Var array
	* @Access Level Protected
	*/
	protected $viewdata = [];

	/*
	* @Var object
	* @Access Level Protected
	*/
	protected $model = null;

	/**
	* @Param none
	* @Access Level Protected
	* return @Array
	*/
	protected function datagroupOptions()
	{
		$dataoptions = Datagroups::orderBy("name", "asc")->get()->groupBy("type");
		$dataoptionOptions = [];
		foreach ($dataoptions as $k => $v) {
			$dataoptionOptions[$k] = [];
			$dataoptionOptions[$k][""] = "Select ".ucwords(str_replace("-", " ", $k));
			foreach ($v as $dataoption) {
				$dataoptionOptions[$k][$dataoption->id] = $dataoption->name;
			}
		}
		return $dataoptionOptions;
	}

	/**
	* @Param $data Array 
	* @Access Level Protected
	* return none
	*/
	protected function flashMessage(Array $data)
	{
		\Session::flash("system_message", ["level"=>$data['level'], "type"=>$data['type'], "message"=>$data['message'],  "access"=>$data['access']]);
	}
	
}

