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
use Ao\Data\Models\Admission;

class AdmissionController extends WcpController{

	public function __construct(Admission $model)
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
			// Load contacts
			$this->viewdata["contacts"] = $this->model->contacts();
			// Load classes
			$classes = $this->model->classes();
			$classArray = [
				"" => "Select Class",
			];
			foreach ($classes as $class) {
				$classArray[$class->id] = $class->name;
			};
			$this->viewdata["classes"] = $classArray;
			
			$this->viewdata["menu"] = 7;
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
		// Load view
		return View::make('wcp::pages.admission.list', $this->viewdata);
	}


	/*
	* Load form for creating a new resource
	*/
	public function create()
	{
		// Load view
		return View::make('wcp::pages.admission.create', $this->viewdata);
	}

	/*
	* Loads a specified resource
	*/
	public function show($id)
	{
		// Get list of items
		$item = $this->model->with(["contactdata1"=>function($query){
			$query->with("icon");
		},"contactdata2"=>function($query){
			$query->with("icon");
		},"aclass"])->find($id);
		// validate model
		if (count($item) < 1) { \App::abort(404); }
		// Load view data
		$this->viewdata["item"] = $item;
		// Load view
		return View::make('wcp::pages.admission.show', $this->viewdata);
	}

	/*
	* Load form for editting a specific resource
	*/
	public function edit($id)
	{
		// Get list of items
		$item = $this->model->find($id);
		// validate model
		if (count($item) < 1) { \App::abort(404); }
		// Load view data
		$this->viewdata["item"] = $item;
		// Load view
		return View::make('wcp::pages.admission.edit', $this->viewdata);
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
					"class_id" => "required|exists:tprl_classes,id",
					"title" => "required",
					"description" => "required",
					"contact1" => "required|different:contact2|exists:tprl_contact_data,id",
					"contact2" => "different:contact1,exists:tprl_contact_data,id",
					"image" => "required|max:1200",
					"close_date" => "required",
					"open_date" => "required",
		];
		// Validation Messages
		$messages = [
					"title.required" => "The admission campaign title is required.",
					"image.required" => "please select an image to upload.",
					"image.max" => "File size limit of 1200kb exceeded.",
					"description.required" => "The admission campaign description is required.",
					"contact1.required" => "Please select a contact medium for new admission campaign.",
					"contact1.exists" => "Selected contact data for contact 1 was not found.",
					"contact1.different" => "Please select a different contact medium for contact 1.",
					"contact2.exists" => "Selected contact data for contact 2 was not found.",
					"contact2.different" => "Please select a different contact medium for contact 2.",
					"class_id.required" => "Please select a class for this admission campaign.",
					"class_id.exists" => "The selected class was not found.",
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
			return Redirect::to('admin/admission/create')->withInput();
		}
		if (!Input::hasfile('image')) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>'please select an image to upload.']);
			// Redirect back
			return Redirect::to('admin/admission/create')->withInput();
		}
		$file = Input::file('image');
			$thumbnail = $file;
			$filename = $file->getClientOriginalName();
			$filename = md5($filename.time().rand(0,10000000));
			$thumb_filename = md5($filename.'thumbnail');
			$ext = $file->getClientOriginalExtension();
			$path = "data/img/admission/";
			$thumb_path = "data/img/admission/thumbnail/";
			// Move main image to destination folder
			$file->move($path, $filename.".".$ext);
			// Move thumbnail to thumbnail destination folder
			File::copy($path.$filename.".".$ext, $thumb_path.$thumb_filename.".".$ext);
		// Prep new data
		$newData = [
			"class_id" => $data["class_id"],
			"title" => $data["title"],
			"description" => $data["description"],
			"contact1" => $data["contact1"],
			"contact2" => $data["contact2"],
			"image" => $path.$filename.".".$ext,
			"thumbnail" => $thumb_path.$thumb_filename.".".$ext,
			"open_date" => date("Y-m-d h:i:s", strtotime($data["open_date"])),
			"close_date" => date("Y-m-d h:i:s", strtotime($data["close_date"]))
		];
		// Save new data
		$model = $this->model->create($newData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"New admission created."]);
		// Redirect back
		return Redirect::to('admin/admission/'.$model->id);
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
					"class_id" => "required|exists:tprl_classes,id",
					"title" => "required",
					"description" => "required|min:12",
					"contact1" => "required|different:contact2|exists:tprl_contact_data,id",
					"contact2" => "different:contact1,exists:tprl_contact_data,id",
					"image" => "max:1200",
					"close_date" => "required",
					"open_date" => "required",
		];
		// Validation Messages
		$messages = [
					"title.required" => "The admission campaign title is required.",
					"image.max" => "File size limit of 1200kb exceeded.",
					"description.required" => "The admission campaign description is required.",
					"description.min" => "The admission campaign description is to short.",
					"contact1.required" => "Please select a contact medium for new admission campaign.",
					"contact1.exists" => "Selected contact data for contact 1 was not found.",
					"contact1.different" => "Please select a different contact medium for contact 1.",
					"contact2.exists" => "Selected contact data for contact 2 was not found.",
					"contact2.different" => "Please select a different contact medium for contact 2.",
					"class_id.required" => "Please select a class for this admission campaign.",
					"class_id.exists" => "The selected class was not found.",
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
		// Find model
		$item = $this->model->find($id);
		if (count($item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>'Admission list not found.']);
			// Redirect back
			return Redirect::back();
		}
		// Prep update data
		$updateData = [
			"class_id" => $data["class_id"],
			"title" => $data["title"],
			"description" => $data["description"],
			"contact1" => $data["contact1"],
			"contact2" => $data["contact2"],
			"open_date" => date("Y-m-d h:i:s", strtotime($data["open_date"])),
			"close_date" => date("Y-m-d h:i:s", strtotime($data["close_date"]))
		];
		// Upload file
		if (Input::hasfile('image')) {
			$file = Input::file('image');
			$thumbnail = $file;
			$filename = $file->getClientOriginalName();
			$filename = md5($filename.time().rand(0,10000000));
			$thumb_filename = md5($filename.'thumbnail');
			$ext = $file->getClientOriginalExtension();
			$path = "data/img/admission/";
			$thumb_path = "data/img/admission/thumbnail/";
			// Move main image to destination folder
			$file->move($path, $filename.".".$ext);
			// Move thumbnail to thumbnail destination folder
			File::copy($path.$filename.".".$ext, $thumb_path.$thumb_filename.".".$ext);
			$updateData["image"] = $path.$filename.".".$ext;
			$updateData["thumbnail"] = $thumb_path.$thumb_filename.".".$ext;
			if (File::exists($item->image)) { File::delete($item->image); }
			if (File::exists($item->thumbnail)) { File::delete($item->thumbnail); }
		}
		// Save new data
		$item->update($updateData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"Admission campaign updated."]);
		// Redirect back
		return Redirect::back();
	}

	/*
	* Save new resource
	*/
	public function updateitem($id, $id2)
	{
		// Request data
		$data = Input::all();
		// Validation Rules
		$rules = [
					"name" => "required",
					"email" => "required|email",
					"phone" => "required|numeric",
		];
		// Validation Messages
		$messages = [
					"name.required" => "The name is required.",
					"email.required" => "The email field is required.",
					"email.email" => "Please enter a valide email address at the email field.",
					"phone.required" => "the phone field is required.",
					"phone.numeric" => "The phone number must be numeric.",
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
		// Find model
		$item = $this->model->with(["item"=>function($query) use($id2){
			$query->find($id2);
		}])->find($id);
		if (count($item) < 1 || count($item->item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"List item not found."]);
			// Redirect back
			return Redirect::back();
		}
		$updateData = [
			"name" => $data["name"],
			"email" => $data["email"],
			"phone" => $data["phone"],
			"message" => $data["message"]
		];
		// Update model
		$item->item->update($updateData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"Admission list item updated."]);
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
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Admission list deleted."]);
		// Redirect back
		return Redirect::to("admin/admission");
	}

	/*
	* Delete an existing resource
	*/
	public function destroyitem($id, $id2)
	{
		$this->dodestroyitem($id, $id2);
		// Flash message
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Admission list item deleted."]);
		// Redirect back
		return Redirect::to("admin/admission/".$id."/list");
	}

	/*
	* Delete an existing list of resource
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
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Selected admission campaign deleted."]);
		// Redirect back
		return Redirect::to("admin/admission");
	}

	/*
	* Delete an existing list of resource
	*/
	public function bulkDestroyItems($id)
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
		foreach ($data["list"] as $id2) {
			$this->dodestroyitem($id, $id2);
		};
		// Flash message
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Selected admission campaign items deleted."]);
		// Redirect back
		return Redirect::to("admin/admission/".$id."/list");
	}

	/*
	* Delete an existing resource
	*/
	public function dodestroy($id)
	{
		// Find model
		$item = $this->model->find($id);
		// Validate model
		if (count($item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Admission list does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		// Delete admission current image
		if (File::exists($item->image)) { File::delete($item->image); }
		if (File::exists($item->thumbnail)) { File::delete($item->thumbnail); }
		// Delete item item
		$item->alist()->delete();
		// Delete item
		$item->delete();
	}

	/*
	* Delete an existing resource
	*/
	public function dodestroyitem($id, $id2)
	{
		// Find model
		$item = $this->model->with(["item"=>function($query) use($id2){
			$query->find($id2);
		}])->find($id);
		// validate model
		if (count($item) < 1 || count($item->item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Admission list item does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		// Delete item item
		$item->item->delete();
	}
} 

