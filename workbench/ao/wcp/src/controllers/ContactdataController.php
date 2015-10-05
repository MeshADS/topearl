<?php namespace Ao\Wcp\Controllers;

use \Input;
use \Redirect;
use \Session;
use \Slugify;
use \View;
use \Validator;
use Ao\Data\Models\Basicdata;
use Ao\Data\Models\Notification;
use Ao\Data\Models\Contactdata;

class ContactdataController extends WcpController{

	public function __construct(Contactdata $model)
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
			$this->viewdata["selectoptions"] = \Config::get("selectoptions.contactdata");
			// Set controller model
			$this->model = $model;
			// Load icons
			$icons = $this->model->allicons();
			$this->viewdata["icons"] = $icons;

			$this->viewdata["menu"] = 2;
		}
	}

	/*
	* Load a list of resources
	*/
	public function index()
	{
		// Get list of items
		$list = $this->model->orderBy("created_at", "desc")->with("icon")->paginate(20);
		// Load view data
		$this->viewdata["list"] = $list;
		$this->viewdata["submenu"] = 2.5;
		// Load view
		return View::make('wcp::pages.contactdata.list', $this->viewdata);
	}

	/*
	* Load a specified resource
	*/
	public function show($item)
	{
		// Get list of items
		$list = $this->model->orderBy("created_at", "desc")->with("icon")->paginate(20);
		// Load view data
		$this->viewdata["list"] = $list;
		$this->viewdata["submenu"] = 2.5;
		// Load view
		return View::make('wcp::pages.contactdata.list', $this->viewdata);
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
					"data" => "required",
					"icon" => "required|exists:tprl_icons,id",
		];
		// Validation Messages
		$messages = [
					"name.required" => "The contact data name is required.",
					"type.required" => "The contact data type is required.",
					"data.required" => "The contact data is required.",
					"icon.required" => "The contact data icon is required.",
					"icon.exists" => "The selected icon does not exist."
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
			return Redirect::to('admin/contact_data')->withInput();
		}
		// Find item with same name and same type
		$item = $this->model->where("type", $data["type"])->where("name", $data["name"])->first();
		// Validate
		if (count($item) > 0) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"That data already exists."]);
			// Redirect back
			return Redirect::to('admin/contact_data')->withInput();
		}
		// Prep new data
		$newData = [
			"name" => $data["name"],
			"type" => $data["type"],
			"color" => $data["color"],
			"icon_id" => $data["icon"],
			"data" => $data["data"]
		];
		// Save new data
		$this->model->create($newData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"New contact data created."]);
		// Redirect back
		return Redirect::to('admin/contact_data');
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
					"data" => "required",
					"icon" => "required|exists:tprl_icons,id",
		];
		// Validation Messages
		$messages = [
					"name.required" => "The contact data name is required.",
					"type.required" => "The contact data type is required.",
					"data.required" => "The contact data is required.",
					"icon.required" => "The contact data icon is required.",
					"icon.exists" => "The selected icon does not exist."
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
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"That data already exists."]);
			// Redirect back
			return Redirect::back();
		}
		// Find model to update
		$item = $this->model->find($id);
		if (count($item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Contact data does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		// Prep new data
		$updateData = [
			"name" => $data["name"],
			"type" => $data["type"],
			"color" => $data["color"],
			"icon_id" => $data["icon"],
			"data" => $data["data"]
		];
		// Save new data
		$item->update($updateData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"Contact data updated."]);
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
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Contact data deleted."]);
		// Redirect back
		return Redirect::to("admin/contact_data");
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
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Contact data deleted."]);
		// Redirect back
		return Redirect::to("admin/contact_data");
	}

	/*
	* Delete an existing resource
	*/
	public function dodestroy($id)
	{
		$item = $this->model->find($id);
		if (count($item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Contact data does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		// Delete the item
		$item->delete();
	}
} 

