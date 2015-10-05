<?php namespace Ao\Wcp\Controllers;

use \Input;
use \Redirect;
use \Session;
use \Slugify;
use \View;
use \Validator;
use Ao\Data\Models\Basicdata;
use Ao\Data\Models\Notification;
use Ao\Data\Models\Menus;

class MenubuilderController extends WcpController{

	public function __construct(Menus $model)
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
			// Set current menu
			$this->viewdata["menu"] = 2;
			// Set color options
			$this->viewdata["coloroptions"] = \Config::get("selectoptions.colors", []);
		}
	}

	/*
	* Load a list of resources
	*/
	public function index()
	{
		// Get models
		$list = $this->model->orderBy("position", "asc")->with("submenus")->where("isslave", 0)->paginate(20);
		// Load view data
		$this->viewdata["list"] = $list;
		$this->viewdata["submenu"] = 2.9;
		// Load view
		return View::make('wcp::pages.menubuilder.list', $this->viewdata);
	}

	/*
	* Load a list of resources
	*/
	public function show($id)
	{
		// Get models
		$parent = $this->model->where("isslave", 0)->where("id", $id)->first();
		// Validate models
		if (!$parent) {
			return \App::abort(404, "Menu item does not exist.");
		}
		$list = $parent->submenus()->orderBy("position", "asc")->paginate(20);
		// Load view data
		$this->viewdata["parent"] = $parent;
		$this->viewdata["list"] = $list;
		$this->viewdata["submenu"] = 2.9;
		// Load view
		return View::make('wcp::pages.menubuilder.item', $this->viewdata);
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
					"url" => "url",
					"position" => "required|numeric",
					"master_id" => "exists:tprl_menus,id"
				];
		// Validation Messages
		$messages = [
					"title.required" => "The title field is required.",
					"title.unique" => "Form already exists.",
					"url.url" => "Menu url must be a valid URL.",
					"position.required" => "The position field is required.",
					"position.url" => "Invalid URL.",
					"master_id.exists" => "Parent menu does not exist."
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
			return Redirect::back()->withInput();
		}
		// Prep new data
		$ext = Input::get("ext", 0);
		$isslave = Input::get("isslave", null);
		$master_id = Input::get("master_id", null);
		$color = Input::get("color", null);
		$newData = [
			"title" => str_replace("'", "", $data["title"]),
			"slug" => Slugify::slugify($data["title"]),
			"url" => $data["url"],
			"isslave" => (is_null($isslave)) ? 0 : 1 ,
			"ext" => $ext,
			"color" => $color,
			"position" => $data["position"],
		];
		if (is_null($master_id)) {
			// Save new data
			$this->model->create($newData);
			// Flash message
			Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"New menu created."]);
			// Redirect back
			return Redirect::back();
		}
		// get master model
		$master = $this->model->find($master_id);
		// Validate model
		if (!$master) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Parent menu not found."]);
			// Redirect back
			return Redirect::back();
		}
		// Save new sub data
		$master->submenus()->create($newData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"New submenu created."]);
		// Redirect back
		return Redirect::back();
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
					"position" => "required|numeric",
					"master_id" => "exists:tprl_menus,master_id"
				];
		// Validation Messages
		$messages = [
					"title.required" => "The title field is required.",
					"title.unique" => "Form already exists.",
					"url.url" => "Menu url must be a valid URL.",
					"position.required" => "The position field is required.",
					"position.url" => "Invalid URL.",
					"master_id.exists" => "Parent menu does not exist."
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
		// Get model
		$menu = $this->model->find($id);
		// Prep new data
		$ext = Input::get("ext", 0);
		$isslave = Input::get("isslave", 0);
		$color = Input::get("color", null);
		$updateData = [
			"title" => str_replace("'", "", $data["title"]),
			"slug" => Slugify::slugify($data["title"]),
			"url" => $data["url"],
			"isslave" => $isslave,
			"ext" => $ext,
			"color" => $color,
			"position" => $data["position"],
		];
		// Save new sub data
		$menu->update($updateData);
		if ($isslave == 1) {
			$flash_message = "Submenu updated successfully.";
		}
		else{
			$flash_message = "Menu updated successfully.";
		}
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>$flash_message]);
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
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Menu item deleted."]);
		// Redirect back
		return Redirect::back();
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
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Selected menu items deleted succefully."]);
		// Redirect back
		return Redirect::back();
	}
	/*
	* Delete an existing resource
	*/
	public function dodestroy($id)
	{
		$parent = $this->model->find($id);
		if (count($parent) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Menu item does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		// Get form submitions
		$submenus = $parent->submenus()->get();
		if (count($submenus) > 0) {
			// Delete submitions
			$submenus->delete();
		}
		// Delete the item
		$parent->delete();
	}
} 

