<?php namespace Ao\Api\Controllers;

use \Schoolcalendar;

class CalendarController extends ApiController{

	public function index()
	{
		$data = \Input::all();
		// Validate the request
		if (!isset($data["month"]) || !isset($data["year"]) ) {
			
			return \Response::json(["status"=>"error", "level"=>"danger", "message"=>"Invalid request!"], 422);
		}
		// Prep date range
		$firstDay = date("Y-m", strtotime($data["month"]." ".$data["year"]))."-01 00:00:00";
		$lastDay = date("Y-m-t", strtotime($data["month"]." ".$data["year"]))." 23:59:59";
		// Get default data
		$query = Schoolcalendar::orderBy("schedule_starts", "asc")
								->where("schedule_starts", ">=", $firstDay)
								->where("schedule_starts", "<=", $lastDay)
								->with(["category"=>function($query){
											$query->where("type", "calendar");
										}])->get();
		// Validate for category
		if (isset($data["category"])){
			// Query with category filter
			$query = Schoolcalendar::orderBy("schedule_starts", "asc")
						->where("schedule_starts", ">=", $firstDay)
						->where("schedule_starts", "<=", $lastDay)
						->where("category_id", $data["category"])
						->with(["category"=>function($query){
									$query->where("type", "calendar");
						}])->get();	
		}
		// Return response
		return \Response::json(["status"=>"success", "level"=>"success", "messgae"=>"Success!", "data"=>$query], 200);
	}

}