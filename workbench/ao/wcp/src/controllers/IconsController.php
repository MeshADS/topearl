<?php namespace Ao\Wcp\Controllers;

use \Input;
use \Redirect;
use \Session;
use \Slugify;
use \View;
use \Validator;
use Ao\Data\Models\Basicdata;
use Ao\Data\Models\Notification;
use Ao\Data\Models\Icons;

class IconsController extends WcpController{

	public function __construct(Icons $model)
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
			// Load select options
			$this->viewdata["selectoptions"] = \Config::get("selectoptions.icons");
			// Set controller model
			$this->model = $model;
			$this->viewdata["menu"] = 2;
		}
	}

	/*
	* Load a list of resources
	*/
	public function index()
	{
		// Get list of items
		$list = $this->model->orderBy("created_at", "desc")->paginate(20);
		// Load view data
		$this->viewdata["list"] = $list;
		$this->viewdata["submenu"] = 2.3;
		// Load view
		return View::make('wcp::pages.icons.list', $this->viewdata);
	}

	/*
	* Load a specified resource
	*/
	public function show($item)
	{
		// Get list of items
		$list = $this->model->orderBy("created_at", "desc")->paginate(20);
		// Load view data
		$this->viewdata["list"] = $list;
		$this->viewdata["submenu"] = 2.3;
		// Load view
		return View::make('wcp::pages.icons.list', $this->viewdata);
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
					"name" => "required",
					"type" => "required",
					"code" => "required",
		];
		// Validation Messages
		$messages = [
					"name.required" => "The icon name is required.",
					"type.required" => "The icon type is required.",
					"code.required" => "The icon code is required."
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
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>$message]);
			// Redirect back
			return Redirect::to('admin/aoicons')->withInput();
		}
		// Find item with same name and same type
		$item = $this->model->where("type", $data["type"])->where("name", $data["name"])->first();
		// Validate
		if (count($item) > 0) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"That icon already exists."]);
			// Redirect back
			return Redirect::to('admin/aoicons')->withInput();
		}
		// Prep new data
		$newData = [
			"name" => $data["name"],
			"slug" => Slugify::slugify($data["name"]),
			"type" => $data["type"],
			"code" => $data["code"],
			"color" => $data["color"]
		];
		// Save new data
		$this->model->create($newData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"New icon created."]);
		// Redirect back
		return Redirect::to('admin/aoicons');
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
					"name" => "required",
					"type" => "required",
					"code" => "required",
		];
		// Validation Messages
		$messages = [
					"name.required" => "The icon name is required.",
					"type.required" => "The icon type is required.",
					"code.required" => "The icon code is required."
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
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>$message]);
			// Redirect back
			return Redirect::back();
		}
		// Find item with same name and same type
		$item = $this->model->where("type", $data["type"])->where("name", $data["name"])->where("id", "!=", $id)->first();
		// Validate
		if (count($item) > 0) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"That icon already exists."]);
			// Redirect back
			return Redirect::back();
		}
		// Find model to update
		$item = $this->model->find($id);
		if (count($item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Icon does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		// Prep new data
		$updateData = [
			"name" => $data["name"],
			"slug" => Slugify::slugify($data["name"]),
			"type" => $data["type"],
			"code" => $data["code"],
			"color" => $data["color"]
		];
		// Save new data
		$item->update($updateData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"Icon updated."]);
		// Redirect back
		return Redirect::back();
	}

	/*
	* Delete an existing resource
	*/
	public function destroy($id)
	{
		$this->dodestroy($id);
		// Flash message
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Icon deleted."]);
		// Redirect back
		return Redirect::to("admin/aoicons");
	}

	/*
	* Bulk delete an existing resource
	*/
	public function bulkDestroy()
	{
		// Request data
		$data = Input::all();
		// Validation Rules
		$rules = [
					"list" => "required",
		];
		// Validation Messages
		$messages = [
					"list" => "Please select items to delete."
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
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>$message]);
			// Redirect back
			return Redirect::back();
		}
		// Delete items
		foreach ($data["list"] as $id) {
			$this->dodestroy($id);
		};
		// Flash message
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Icons deleted."]);
		// Redirect back
		return Redirect::to("admin/aoicons");
	}

	/*
	* Delete an existing resource
	*/
	public function dodestroy($id)
	{
		$item = $this->model->with("contacts")->find($id);
		if (count($item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Icon does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		// Verify type and delete
		if (count($item->contacts) > 0) {
			$item->contacts()->delete();
		}
		// Delete the item
		$item->delete();
	}
} 

