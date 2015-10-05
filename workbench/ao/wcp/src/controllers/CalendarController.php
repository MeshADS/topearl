<?php namespace Ao\Wcp\Controllers;

use \Input;
use \Redirect;
use \Response;
use \Request;
use \Session;
use \Slugify;
use \View;
use \Image;
use \File;
use \Validator;
use Ao\Data\Models\Basicdata;
use Ao\Data\Models\Notification;
use Ao\Data\Models\Calendar;

class CalendarController extends WcpController{

	public function __construct(Calendar $model)
	{
		$this->beforeFilter("auth.wcp");
		// Set default view message
		$this->viewdata["view_message"] = null;
		// Check for flashed site messages
		if (Session::has('system_message')) {
			$system_message = Session::get('system_message');
			if ($system_message["access"] == "wcp") {
				$this->viewdata["view_message"] = $system_message;
			}
		}
		// Load user data
		$user_data = \Sentry::getUser();
		if(count($user_data) > 0){
			$user_data = \Sentry::findUserById($user_data->id);
			$user_group = $user_data->getGroups();
			$this->viewdata["user_data"] = $user_data;
			$this->viewdata["user_group"] = $user_group[0];
			// Load basic data
			$basic_info = Basicdata::orderBy("created_at", "desc")->first();
			$this->viewdata["basic_info"] = $basic_info;
			// Load notifications
			$notifications = Notification::orderBy("read", "desc")->where("user_id", $user_data->id)->orWhere('group_id', $user_group[0]->id)->with('type')->paginate();
			$unread_notifications = Notification::where("user_id", $user_data->id)->orWhere('group_id', $user_group[0]->id)->where('read', 0)->count();
			$this->viewdata["notifications"] = $notifications;
			$this->viewdata["unread_notifications"] = $unread_notifications;
			// Set controller model
			$this->model = $model;
			// Get categories
			$categories = $this->model->categories();
			$catArr = [
						"" => "Select a catedgory"
						];
			foreach ($categories as  $v) {
				$catArr[$v->id] = $v->name;
			};
			$this->viewdata["categories"] = $catArr;

			$this->viewdata["menu"] = 5;

		}
	}

	/*
	* Load a list of resources
	*/
	public function index()
	{
		// Get list of items
		$list = $this->model->orderBy("schedule_starts", "asc")->get();
		// Load view data
		$this->viewdata["list"] = $list;
		// Load view
		return View::make('wcp::pages.calendar.list', $this->viewdata);
	}

	/*
	* Save new resource
	*/
	public function store()
	{
		// Request data
		$data = Input::all();
		// Validation Rules
		$rules = [
					"title" => "required",
					"schedule_starts" => "required",
					"schedule_ends" => "required",
					"category_id" => "required|exists:tprl_categories,id"
		];
		// Validation Messages
		$messages = [
					"title.required" => "The event title is required.",
					"schedule_starts.required" => "The start date is required.",
					"schedule_ends.required" => "The end date is required.",
					"category_id.required" => "Please select a category for this event.",
					"category_id.exists" => "The selected category does not exist.",
		];
		// Validation
		$validation = Validator::make($data, $rules, $messages);
		// Do validations
		if ($validation->fails()) {
			// Validation errors
			$messages = $validation->errors()->getMessages();
			$message = "";
			// Loop through validation errors
			foreach($messages as $m){
				$message .= $m[0]."<br>";
			}
			if (Request::ajax()) {
				return Response::json(["level"=>"danger",  "access"=>"wcp", "status"=>"error", "message"=>$message], 422);
			}
			else{
				// Flash message
				Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>$message]);
				// Redirect back
				return Redirect::to('admin/calendar')->withInput();
			}
		}
		// Prep new data
		$schedule_starts = str_replace("T", " ", $data["schedule_starts"]);
		$schedule_ends = str_replace("T", " ", $data["schedule_ends"]);
		$newData = [
			"title" => $data["title"],
			"slug" => Slugify::slugify($data["title"]),
			"category_id" => $data["category_id"],
			"schedule_starts" => $schedule_starts,
			"schedule_ends" => $schedule_ends
		];
		// Save new data
		$model = $this->model->create($newData);
		$respData = [
			'title'=>$model->title,
			'start'=>$model->schedule_starts,
			'end'=>$model->schedule_ends,
			'class'=>'bg-complete-lighter',
			'other' => [
				'slug'=>$model->slug,
				'category_id'=>$model->category_id,
				'id' => $model->id
			]
		];
		if (Request::ajax()) {
				return Response::json(["level"=>"success",  "access"=>"wcp", "status"=>"success", "message"=>"Event saved.", "model"=>$respData], 200);
		}
		else{
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>'Event saved.']);
			// Redirect back
			return Redirect::to('admin/calendar');
		}
	}

	/*
	* Update an existing resource
	*/
	public function update($id)
	{
		// Request data
		$data = Input::all();
		// Validation Rules
		$rules = [
					"title" => "required",
					"schedule_starts" => "required",
					"schedule_ends" => "required",
					"category_id" => "required|exists:tprl_categories,id"
		];
		// Validation Messages
		$messages = [
					"title.required" => "The event title is required.",
					"schedule_starts.required" => "The start date is required.",
					"schedule_ends.required" => "The end date is required.",
					"category_id.required" => "Please select a category for this event.",
					"category_id.exists" => "The selected category does not exist.",
		];
		// Validation
		$validation = Validator::make($data, $rules, $messages);
		// Do validations
		if ($validation->fails()) {
			// Validation errors
			$messages = $validation->errors()->getMessages();
			$message = "";
			// Loop through validation errors
			foreach($messages as $m){
				$message .= $m[0]."<br>";
			}
			if (Request::ajax()) {
				return Response::json(["level"=>"danger",  "access"=>"wcp", "status"=>"error", "message"=>$message], 422);
			}
			else{
				// Flash message
				Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>$message]);
				// Redirect back
				return Redirect::to('admin/calendar')->withInput();
			}
		}
		// Find model to update
		$item = $this->model->find($id);
		if (count($item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Calendar does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		// Prep update data
		$schedule_starts = str_replace("T", " ", $data["schedule_starts"]);
		$schedule_ends = str_replace("T", " ", $data["schedule_ends"]);
		$updateData = [
			"title" => $data["title"],
			"slug" => Slugify::slugify($data["title"]),
			"category_id" => $data["category_id"],
			"schedule_starts" => $schedule_starts,
			"schedule_ends" => $schedule_ends
		];
		$item->update($updateData);
		$respData = [
			'title'=>$updateData['title'],
			'start'=>$updateData['schedule_starts'],
			'end'=>$updateData['schedule_ends'],
			'class'=>'bg-complete-lighter',
			'other' => [
				'slug'=>$updateData['slug'],
				'category_id'=>$data["category_id"],
				'id' => $item->id
			]
		];
		if (Request::ajax()) {
				return Response::json(["level"=>"success",  "access"=>"wcp", "status"=>"success", "message"=>"Event updated successfully.", "model"=>$respData], 200);
		}
		else{
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>'Event updated successfully.']);
			// Redirect back
			return Redirect::to('admin/calendar');
		}
	}

	/*
	* Delete an existing resource
	*/
	public function destroy($id)
	{
		$this->dodestroy($id);
		if (Request::ajax()) {
					return Response::json(["level"=>"success",  "access"=>"wcp", "status"=>"success", "message"=>"Event successfully deleted."], 200);
		}
		else{
			// Flash message
			Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Event successfully deleted."]);
			// Redirect back
			return Redirect::to("admin/calendar");
		}
	}

	/*
	* Delete an existing resource
	*/
	public function dodestroy($id)
	{
		$item = $this->model->find($id);
		if (count($item) < 1) {
			if (Request::ajax()) {
					return Response::json(["level"=>"danger",  "access"=>"wcp", "status"=>"error", "message"=>"Event not found."], 404);
			}
			else{
				// Flash message
				Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Event not found."]);
				// Redirect back
				return Redirect::back();
			}
				
		}
		// Delete calendar current image
		if (File::exists($item->image)) { File::delete($item->image); }
		if (File::exists($item->thumbnail)) { File::delete($item->thumbnail); }
		// Delete the item
		$item->delete();
	}
} 

