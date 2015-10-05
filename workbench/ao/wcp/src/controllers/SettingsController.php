<?php namespace Ao\Wcp\Controllers;

use \Input;
use \Redirect;
use \Session;
use \Slugify;
use \View;
use \Validator;
use \Sentry;
use \Image;
use Ao\Data\Models\Basicdata;
use Ao\Data\Models\Notification;
use Ao\Data\Models\Contactdata;

class SettingsController extends WcpController{

	public function __construct()
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

			$this->viewdata["menu"] = 9;
		}
	}

	/*
	* Load basic info
	*/
	public function basic_info()
	{
		// Get list of items
		$basic_info = Basicdata::first();
		// Load view data
		$this->viewdata["basic_info"] = $basic_info;
		$this->viewdata["submenu"] = 9.1;
		// Load view
		return View::make('wcp::pages.settings.basic_info', $this->viewdata);
	}

	/*
	* Update basic info
	*/
	public function basic_info_edit($id)
	{
		// Retrieve request data
		$data = Input::all();
		// Validation rules
		$rules = [
				"shortname" => "required",
				"fullname" => "required",
				"description" => "required"
		];
		// Validation Messages
		$messages = [
				"shortname.required" => "The short name field is required",
				"fullname.required" => "The full name field is required",
				"description.required" => "The description field is required"
		];
		// Validation
		$validation = Validator::make($data, $rules, $messages);
		// Check validation
		if ($validation->fails()) {
			$errMessages = $validation->errors()->getMessages();
			$message = "";
			foreach ($errMessages as $m) {
				$message .= $m[0]."<br>";
			}
			// Flash message
			Session::flash("system_message", ["level"=>"danger", "access"=>"wcp", "type"=>"page", "message"=>$message]);
			// Return to page
			return Redirect::to("admin/basic_info");
		}
		// Find basic info model
		$item = Basicdata::find($id);
		// Validate model
		if (count($item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger", "access"=>"wcp", "type"=>"page", "message"=>"Invalid request made."]);
			// Return to page
			return Redirect::to("admin/basic_info");
		}
		// Prep data
		$updateData = [
			"shortname" => $data["shortname"],
			"fullname" => $data["fullname"],
			"description" => $data["description"]
		];
		// Save new data
		$item->update($updateData);
		// Flash message
		Session::flash("system_message", ["level"=>"success", "access"=>"wcp", "type"=>"page", "message"=>"Basic info updated."]);
		// Return to page
		return Redirect::to("admin/basic_info");
	}


	/*
	* Logo
	*/
	/*
	* Update basic info
	*/
	public function basic_info_logo($id)
	{
		// Retrieve request data
		$data = Input::all();
		// Validation rules
		$rules = [
				"image" => "required|max:1200",
				"type" => "required",
		];
		// Validation Messages
		$messages = [
				"image.required" => "Please select a file to upload.",
				"image.max" => "Maximum file upload size of 1200kb exceded.",
				"type.required" => "Please select a logo type to upload.",
		];
		// Validation
		$validation = Validator::make($data, $rules, $messages);
		// Check validation
		if ($validation->fails()) {
			$messages = $validation->errors()->getMessages();
			$message = "";
			foreach ($messages as $m) {
				$message .= $m[0]."<br>";
			}
			// Flash message
			Session::flash("system_message", ["level"=>"danger", "access"=>"wcp", "type"=>"page", "message"=>$message]);
			// Return to page
			return Redirect::to("admin/basic_info");
		}
		// Find basic info model
		$item = Basicdata::find($id);
		// Validate model
		if (count($item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger", "access"=>"wcp", "type"=>"page", "message"=>"Invalid request made."]);
			// Return to page
			return Redirect::to("admin/basic_info");
		}
		// Upload image
		$file = Input::file("image");
		$filename = $file->getClientOriginalName();
		$filename = md5($filename.time().rand(0,1000000));
		$ext = $file->getClientOriginalExtension();
		$path = "data/img/rsrc/";
		// Move main image to destination folder
		$file->move($path, $filename.".".$ext);
		// Prep data
		$updateData = [
			$data["type"] => $path.$filename.".".$ext
		];
		// Save new data
		$item->update($updateData);
		// Flash message
		Session::flash("system_message", ["level"=>"success", "access"=>"wcp", "type"=>"page", "message"=>"Basic info logo updated."]);
		// Return to page
		return Redirect::to("admin/basic_info");
	}

} 

