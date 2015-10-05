<?php namespace Ao\Wcp\Controllers;

use \Input;
use \Redirect;
use \Session;
use \Slugify;
use \View;
use \Validator;
use \File;
use Ao\Data\Models\Basicdata;
use Ao\Data\Models\Notification;
use Ao\Data\Models\Files;
use Ao\Data\Models\Datagroups;
use Ao\Wcp\Acme\Imageresize;

class FilemangerController extends WcpController{
	
	private $file = null;

	public function __construct(Files $model)
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
			// Get/Set typeoptions
			$typeoptions = $this->datagroupOptions();
			$this->viewdata["typeoptions"] = (isset($typeoptions["file-type"])) ? $typeoptions["file-type"] : [];
		}
	}

	/*
	* Load a list of resources
	*/
	public function index()
	{
		// Get models
		$list = $this->model->orderBy("created_at", "desc")->orderBy("name", "asc")->with(["type"])->paginate(20);
		// Load view data
		$this->viewdata["list"] = $list;
		$this->viewdata["submenu"] = 2.11;
		// Load view
		return View::make('wcp::pages.filemanager.list', $this->viewdata);
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
		$this->viewdata["submenu"] = 2.10;
		// Load view
		return View::make('wcp::pages.filemanager.item', $this->viewdata);
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
					"file" => "required|max:8192",
					"type_id" => "required|exists:tprl_datagroups,id",
				];
		// Validation Messages
		$messages = [
					"file.required" => "Please select a file to upload.",
					"file.max" => "Maximum file size of 8192kb(8.1mb) exceeded.",
					"type.required" => "Please select file type.",
					"type.exists" => "Selected file type does not exist.",
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
		$this->file = Input::file('file', null);
		$type = Datagroups::find($data["type_id"]);
		$data["file"] = $this->file;
		$filename = $this->file->getClientOriginalName();
		$filesize = $this->file->getSize();
		$filesize = $filesize / 1024;
		$filesize = round($filesize);
		$filesize = (strlen($filesize) > 3) ? round(($filesize / 1024))."Mb" : $filesize."Kb" ;
		$data["file_ext"] = $this->file->getClientOriginalExtension();
		$data["file_name"] = $filename;
		$data["file_size"] = $filesize;
		$data["isImage"] = in_array($data["file_ext"], $this->exts["image"]);
		$data["isVideo"] = in_array($data["file_ext"], $this->exts["video"]);
		$data["isDoc"] = in_array($data["file_ext"], $this->exts["doc"]);
		$data["isCompressed"] = in_array($data["file_ext"], $this->exts["compressed"]);
		$data["isAudio"] = in_array($data["file_ext"], $this->exts["audio"]);
		$data["isApplication"] = in_array($data["file_ext"], $this->exts["application"]);
		// Upload file
		$ext = $this->file->getClientOriginalExtension();
		$filename = md5(time().$data["file_name"]);
		$path = 'data/files/';
		// Move main image to destination folder
		$this->file->move($path, $filename.".".$data["file_ext"]);
		$data["uploaded_path"] = $path.$filename.".".$data["file_ext"];
		$downloadable = Input::get("downloadable", null);
		// Process new data
		$newFile = [
			"url" => $path.$filename.".".$data["file_ext"],
			"name" => preg_replace("/(.+)\.(\w+)/", "$1", $data["file_name"]),
			"type_id" => $data["type_id"],
			"thumbnail" => "",
			"downloadable" => $downloadable,
			"downloadkey" => (is_null(Input::get("downloadable", null))) ? null : md5(time().round(100,10000)),
			"info" => [
						'name' => preg_replace("/(.+)\.(\w+)/", "$1", $data["file_name"]),
						'original_name' => $data["file_name"],
						'extention' => $data["file_ext"],
						'type' => $type->name,
						'size' => $data["file_size"],
					],
		];
		$this->file = Input::file('file');
		// Process file
		$processed = $this->process($data);
		$newFile["thumbnail"] = $processed["thumbnail"];
		$newFile["info"] = serialize($newFile["info"]);
		// Save info
		$this->model->create($newFile);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"File successfully uploaded."]);
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
					"file" => "required|max:8192",
					"type_id" => "required|exists:tprl_datagroups,id",
				];
		// Validation Messages
		$messages = [
					"file.required" => "Please select a file to upload.",
					"file.max" => "Maximum file size of 8192kb(8.1mb) exceeded.",
					"type.required" => "Please select file type.",
					"type.exists" => "Selected file type does not exist.",
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
		// Get item model
		$item = $this->model->find($id);
		// Validate model
		if (!$item) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"File not found!"]);
			// Redirect back
			return Redirect::back();
		}
		// Process new data
		$updateData = [
			"type_id" => $data["type_id"],
			"downloadable" => Input::get("downloadable", null),
			"downloadkey" => (is_null(Input::get("downloadable", null))) ? null : md5(time().round(100,10000))  
		];
		// Save info
		$item->update($updateData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"File successfully updated."]);
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
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"File deleted."]);
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
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Selected files deleted succefully."]);
		// Redirect back
		return Redirect::back();
	}
	/*
	* Delete an existing resource
	*/
	public function dodestroy($id)
	{
		$item = $this->model->with(["type"])->find($id);
		if (!$item) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"File does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		// Get item type
		$type = $item->type;
		// Delete item files
		if (File::exists($item->url)) {
			File::delete($item->url);
		}
		// Delet item thumbnail
		if ($type->slug == "image" && File::exists($item->thumbnail)) {
			File::delete($item->thumbnail);
		}
		// Delete the item
		$item->delete();
	}

	/*
	* Processes file for extra info
	*/
	private function process($data)
	{
		$processed = [];
		$thumb_name = md5(time().$data["uploaded_path"]).".jpg";

		if ($data["isImage"]) {
			// Initialize / load image
			$resizeObj = new Imageresize($data["uploaded_path"]);
			// Resize image (options: exact, portrait, landscape, auto, crop)
			$resizeObj -> resizeImage(256, null, 'auto');
			// Save image
			$resizeObj -> saveImage("data/files/thumb/".$thumb_name, 100);
			// Update thumbnail path
			$processed["thumbnail"] = "data/files/thumb/".$thumb_name;
		}
		elseif ($data["isVideo"]) {
			// Update thumbnail path
			$processed["thumbnail"] = "assets/wcp/img/filemanager/video.png";
		}
		elseif ($data["isAudio"]) {
			// Update thumbnail path
			$processed["thumbnail"] = "assets/wcp/img/filemanager/audio.png";
		}
		elseif ($data["isCompressed"]) {
			// Update thumbnail path
			$processed["thumbnail"] = "assets/wcp/img/filemanager/compressed.png";
		}
		elseif ($data["isApplication"]) {
			// Update thumbnail path
			$processed["thumbnail"] = "assets/wcp/img/filemanager/app.png";
		}
		elseif ($data["isDoc"]) {
			// Update thumbnail path
			$processed["thumbnail"] = "assets/wcp/img/filemanager/doc.png";
		}
		else{
			// Update thumbnail path
			$processed["thumbnail"] = "assets/wcp/img/filemanager/other.png";
		}
		// Return processed data
		return $processed;
	}

	/*
	* Load a list of resources
	*/
	public function api()
	{
		// Fetch data
		$data = $this->model->orderBy("created_at", "desc")->orderBy("name", "asc")->with(["type"])->paginate(20);
		// Return response
		return \Response::json(["status"=>"success", "level"=>"success", "messgae"=>"Success!", "data"=>$data], 200);
	}
} 

