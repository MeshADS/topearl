<?php namespace Ao\Wcp\Controllers;

use \Input;
use \Redirect;
use \Session;
use \Slugify;
use \View;
use \Validator;
use Ao\Data\Models\Basicdata;
use Ao\Data\Models\Notification;
use Ao\Data\Models\Programs;

class ProgramsController extends WcpController{

	private $path = "data/img/images/";

	public function __construct(Programs $model)
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
			// Load select options
			$this->viewdata["selectoptions"] = $this->model->types();

			$this->viewdata["menu"] = 4;
		}
	}

	/*
	* Load a list of resources
	*/
	public function index()
	{
		// Get list of items
		$list = $this->model->orderBy("position", "asc")->with(["type", "users"])->paginate(20);
		// Load view data
		$this->viewdata["list"] = $list;
		// Load view
		return View::make('wcp::pages.programs.list', $this->viewdata);
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
					"type_id" => "required|exists:tprl_datagroups,id",
					"image" => "required|mimes:jpeg,jpg,png",
					"description" => "required",
					"position" => "required|numeric|min:0",
		];
		// Validation Messages
		$messages = [
					"name.required" => "The program name is required.",
					"type_id.required" => "Please select a type for this program.",
					"type_id.exists" => "The selected program type does not exist.",
					"image.required" => "Please select an image to upload.",
					"image.mimes" => "Invalid image file format, image must be jpg, jpeg or png.",
					"description.required" => "Please enter a description for this program.",
					"position.required" => "Please enter an order position for this program.",
					"position.numeric" => "Please enter only numeric values for order position.",
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
				for ($i=0; $i < count($m); $i++) { 
					$message .= $m[$i]."</br>";
				}
			}
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>$message]);
			// Redirect back
			return Redirect::to('admin/programs')->withInput();
		}
		// Find item with same name and same type
		$item = $this->model->where("type_id", $data["type_id"])->where("name", $data["name"])->first();
		// Validate
		if ($item) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"A program bearing that name already exists."]);
			// Redirect back
			return Redirect::to('admin/programs')->withInput();
		}
		// Prep new data
		$newData = [
			"name" => $data["name"],
			"type_id" => $data["type_id"],
			"description" => $data["description"],
			"position" => $data["position"]
		];
		// Upload image
		$file = Input::file("image");
		$name = md5(time().$file->getClientOriginalName());
		$ext = $file->getclientOriginalExtension();
		$image = \Image::make($file, 70)
						->resize(480, null, function($constrain){ 
							$constrain->aspectRatio(); 
						})
						->save($this->path.$name.".".$ext);
		// Update new data
		$newData["image"] = $this->path.$name.".".$ext;
		// Save new data
		$this->model->create($newData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"New program created."]);
		// Redirect back
		return Redirect::to('admin/programs');
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
					"type_id" => "required|exists:tprl_datagroups,id",
					"image" => "mimes:jpeg,jpg,png",
					"description" => "required",
					"position" => "required|numeric|min:0",
		];
		// Validation Messages
		$messages = [
					"name.required" => "The program name is required.",
					"type_id.required" => "Please select a type for this program.",
					"type_id.exists" => "The selected program type does not exist.",
					"image.mimes" => "Invalid image file format, image must be jpg, jpeg or png.",
					"description.required" => "Please enter a description for this program.",
					"position.required" => "Please enter an order position for this program.",
					"position.numeric" => "Please enter only numeric values for order position.",
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
				for ($i=0; $i < count($m); $i++) { 
					$message .= $m[$i]."</br>";
				}
			}
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>$message]);
			// Redirect back
			return Redirect::back();
		}
		// Find item with same name and same type
		$exists = $this->model->where("id", "!=", $id)->where("type_id", $data["type_id"])->where("name", $data["name"])->first();
		// Validate
		if ($exists) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"A program bearing that name already exists."]);
			// Redirect back
			return Redirect::back();
		}
		// Find requested model
		$item = $this->model->find($id);
		// Prep update data
		$updateData = [
			"name" => $data["name"],
			"type_id" => $data["type_id"],
			"description" => $data["description"],
			"position" => $data["position"]
		];
		// Check for file
		if (Input::hasFile("image")) {
			// Upload image
			$file = Input::file("image");
			$name = md5(time().$file->getClientOriginalName());
			$ext = $file->getclientOriginalExtension();
			$image = \Image::make($file, 70)
							->resize(480, null, function($constrain){ 
								$constrain->aspectRatio(); 
							})
							->save($this->path.$name.".".$ext);
			// Update update data
			$updateData["image"] = $this->path.$name.".".$ext;
			// Delete current image
			if (\File::exists($item->image)) {
				\File::delete($item->image);
			}
		}
		// Update data
		$item->update($updateData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"New program created."]);
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
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Program deleted."]);
		// Redirect back
		return Redirect::to("admin/programs");
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
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Programs deleted."]);
		// Redirect back
		return Redirect::to("admin/programs");
	}

	/*
	* Delete an existing resource
	*/
	public function dodestroy($id)
	{
		$item = $this->model->find($id);
		// Detach users
		$item->users()->detach();
		// Delete the item
		$item->delete();
	}
} 

