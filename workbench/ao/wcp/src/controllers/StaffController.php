<?php namespace Ao\Wcp\Controllers;

use \Input;
use \Redirect;
use \Session;
use \Slugify;
use \View;
use \Image;
use \File;
use \Validator;
use Ao\Data\Models\Basicdata;
use Ao\Data\Models\Notification;
use Ao\Data\Models\Staff;

class StaffController extends WcpController{

	public function __construct(Staff $model)
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

			$this->viewdata["menu"] = 3;
		}
	}

	/*
	* Load a list of resources
	*/
	public function index()
	{
		// Get list of items
		$list = $this->model->orderBy("pos", "asc")->paginate(20);
		// Load view data
		$this->viewdata["list"] = $list;
		// Load view
		return View::make('wcp::pages.staff.list', $this->viewdata);
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
					"image" => "required|max:1200",
					"description" => "required",
					"office" => "required",
					"pos" => "required|numeric|min:1"
		];
		// Validation Messages
		$messages = [
					"name.required" => "The staff name is required.",
					"image.required" => "please select an image to upload.",
					"image.max" => "File size limit of 1200kb exceeded.",
					"description.required" => "The staff description is required.",
					"office.required" => "The staff office is required.",
					"pos.required" => "Please select order position for this data.",
					"pos.numeric" => "The order position value must be a number.",
					"pos.min" => "Minimum value for the order position number is 1."
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
			return Redirect::to('admin/staff')->withInput();
		}
		if (!Input::hasfile('image')) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>'please select an image to upload.']);
			// Redirect back
			return Redirect::to('admin/staff')->withInput();
		}
		$file = Input::file('image');
		$thumbfile = Input::file('image');
		$filename = $file->getClientOriginalName();
		$filename = md5($filename.time().rand(0,10000000));
		$thumbnail = md5($filename.time().rand(0,10000000).'thumbnail');
		$ext = $file->getClientOriginalExtension();
		$thumbs_path = "data/img/staff/thumbnail/";
		$path = "data/img/staff/";
		// Move main image to destination folder
		$file->move($path, $filename.".".$ext);
		// Move thumbnail to thumbnail destination folder
		File::copy($path.$filename.".".$ext, $thumbs_path.$thumbnail.".".$ext);
		// Prep new data
		$newData = [
			"name" => $data["name"],
			"description" => $data["description"],
			"office" => $data["office"],
			"pos" => $data["pos"],
			"image" => $path.$filename.".".$ext,
			"thumbnail" => $thumbs_path.$thumbnail.".".$ext
		];
		// Save new data
		$this->model->create($newData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"New staff created."]);
		// Redirect back
		return Redirect::to('admin/staff');
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
					"image" => "max:1200",
					"description" => "required",
					"office" => "required",
					"pos" => "required|numeric"
		];
		// Validation Messages
		$messages = [
					"name.required" => "The staff name is required.",
					"image.max" => "File size limit of 1200kb exceeded.",
					"description.required" => "The staff description is required.",
					"office.required" => "The staff office is required.",
					"pos.required" => "Please select order position for this data.",
					"pos.numeric" => "The order value must be a number.",
					"pos.min" => "Minimum value for the order number is 1."
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
		// Find model to update
		$item = $this->model->find($id);
		if (count($item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Staff does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		// Prep new data
		$updateData = [
			"name" => $data["name"],
			"description" => $data["description"],
			"office" => $data["office"],
			"pos" => $data["pos"]
		];
		if (Input::hasfile('image')) {
			$file = Input::file('image');
			$thumbfile = Input::file('image');
			$filename = $file->getClientOriginalName();
			$filename = md5($filename.time().rand(0,10000000));
			$thumbnail = md5($filename.time().rand(0,10000000).'thumbnail');
			$ext = $file->getClientOriginalExtension();
			$thumbs_path = "data/img/staff/thumbnail/";
			$path = "data/img/staff/";
			// Move main image to destination folder
			$file->move($path, $filename.".".$ext);
			// Move thumbnail to thumbnail destination folder
			File::copy($path.$filename.".".$ext, $thumbs_path.$thumbnail.".".$ext);
			// Delete staff current image
			if (File::exists($item->image)) { File::delete($item->image); }
			if (File::exists($item->thumbnail)) { File::delete($item->thumbnail); }

			$updateData["image"] = $path.$filename.".".$ext;
			$updateData["thumbnail"] = $thumbs_path.$thumbnail.".".$ext;
		}
		
		// Save new data
		$item->update($updateData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"Staff updated."]);
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
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Staff deleted."]);
		// Redirect back
		return Redirect::to("admin/staff");
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
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Staff deleted."]);
		// Redirect back
		return Redirect::to("admin/staff");
	}

	/*
	* Delete an existing resource
	*/
	public function dodestroy($id)
	{
		$item = $this->model->find($id);
		if (count($item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Staff does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		// Delete staff current image
		if (File::exists($item->image)) { File::delete($item->image); }
		if (File::exists($item->thumbnail)) { File::delete($item->thumbnail); }
		// Delete the item
		$item->delete();
	}
} 

