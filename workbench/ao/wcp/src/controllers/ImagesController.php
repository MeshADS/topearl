<?php namespace Ao\Wcp\Controllers;

use \Input;
use \Redirect;
use \Session;
use \Slugify;
use \File;
use \View;
use \Validator;
use Ao\Data\Models\Basicdata;
use Ao\Data\Models\Notification;
use Ao\Data\Models\Images;

class ImagesController extends WcpController{

	public function __construct(Images $model)
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

			$this->viewdata["menu"] = 2;

			$dataOptions = $this->datagroupOptions();

			$this->viewdata["groups"] = $dataOptions["group"];
			// Set color options
			$this->viewdata["coloroptions"] = \Config::get("selectoptions.colors", []);
		}
	}

	/*
	* Load a list of resources
	*/
	public function index()
	{
		// Get list of items
		$list = $this->model->orderBy("group_id", "desc")->orderBy("order", "asc")->with("group")->paginate(20);
		// Load view data
		$this->viewdata["list"] = $list;
		$this->viewdata["submenu"] = 2.7;
		// Load view
		return View::make('wcp::pages.images.list', $this->viewdata);
	}

	/*
	* Load a specified resource
	*/
	public function show($item)
	{
		// Get list of items
		$list = $this->model->orderBy("created_at", "desc")->with("group")->paginate(20);
		// Load view data
		$this->viewdata["list"] = $list;
		$this->viewdata["submenu"] = 2.7;
		// Load view
		return View::make('wcp::pages.images.list', $this->viewdata);
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
					"group_id" => "required",
					"title" => "required",
					"image" => "required|max:1200|mimes:jpeg,bmp,png",
					"order" => "numeric",
					"link_url" => "url",
					"link_title" => "required_with:link_url",
					"link_type" => "required_with:link_title",
					"link_color" => "required_with:link_title",
		];
		// Validation Messages
		$messages = [
					"group_id.required" => "The image's group is required.",
					"title.required" => "The image's title is required.",
					"image.required" => "Please select an image to upload.",
					"image.max" => "Maximum file size of 1200kb exceeded.",
					"image.mimes" => "Invalid file type selected, file must be of type jpeg,bmp,png.",
					"order.numeric" => "Please enter a numeric value in order field.",
					"link_url.url" => "Please enter a valid URL starting with http:// or https://.",
					"link_title.required_with" => "Link title requires url.",
					"link_type.required_with" => "Link type requires title.",
					"link_color.required_with" => "Link color requires title.",
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
			return Redirect::to('admin/images')->withInput();
		}
		// Validate input file
		if (!Input::hasFile('image')) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Please select an image to upload."]);
			// Redirect back
			return Redirect::to('admin/images')->withInput();
		}
		$file = Input::file('image');
		$filename = md5($file->getClientOriginalName().time().rand(0,1000000));
		$ext = $file->getClientOriginalExtension();
		$path = 'data/img/images/';
		$filename = $path.$filename.".".$ext;
		// Move main image to destination folder
		$image = \Image::make($file, 60)->save($filename);
		// Prep new data
		$newData = [
			"group_id" => $data["group_id"],
			"title" => $data["title"],
			"caption" => $data["caption"],
			"image" => $filename,
			"order" => $data["order"],
			"link_url" => $data["link_url"],
			"link_title" => $data["link_title"],
			"link_type" => Input::get("link_type", 1),
			"link_color" => Input::get("link_color", ""),
		];
		// Save new data
		$this->model->create($newData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"New contact data created."]);
		// Redirect back
		return Redirect::to('admin/images');
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
					"group_id" => "required",
					"title" => "required",
					"image" => "max:1200",
					"order" => "numeric",
					"link_url" => "url",
					"link_title" => "required_with:link_url",
					"link_type" => "required_with:link_title",
					"link_color" => "required_with:link_title",
		];
		// Validation Messages
		$messages = [
					"group_id.required" => "The image's group is required.",
					"title.required" => "The image's title is required.",
					"image.max" => "Maximum file size of 1200kb exceeded.",
					"order.numeric" => "Please enter a numeric value in order field.",
					"link_url.url" => "Please enter a valid URL starting with http:// or https://.",
					"link_title.required_with" => "Link title requires url.",
					"link_type.required_with" => "Link type requires title.",
					"link_color.required_with" => "Link color requires title.",
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
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Image does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		// Prep new data
		$updateData = [
			"group_id" => $data["group_id"],
			"title" => $data["title"],
			"caption" => $data["caption"],
			"order" => $data["order"],
			"link_title" => $data["link_title"],
			"link_url" => $data["link_url"],
			"link_type" => Input::get("link_type", 1),
			"link_color" => Input::get("link_color", ""),
		];
		// Upload file
		if (Input::hasFile('image')) {
			$file = Input::file('image');
			$filename = md5($file->getClientOriginalName().time().rand(0,1000000));
			$ext = $file->getClientOriginalExtension();
			$path = 'data/img/images/';
			$filename = $path.$filename.".".$ext;
			// Move main image to destination folder
			$image = \Image::make($file, 60)->save($filename);
			// Prep update data
			$updateData = [
				"image" => $filename
			];
			// Delete current image image file
			if(File::exists($item->image)) { File::delete($item->image); }
		}
		// Save new data
		$item->update($updateData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"Image updated."]);
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
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Image deleted."]);
		// Redirect back
		return Redirect::to("admin/images");
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
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Image deleted."]);
		// Redirect back
		return Redirect::to("admin/images");
	}

	/*
	* Delete an existing resource
	*/
	public function dodestroy($id)
	{
		$item = $this->model->find($id);
		if (count($item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Image does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		// Delete image image file
		if(File::exists($item->image)) { File::delete($item->image); }
		// Delete the item
		$item->delete();
	}
} 

