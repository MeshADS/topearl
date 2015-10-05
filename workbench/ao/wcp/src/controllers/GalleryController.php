<?php namespace Ao\Wcp\Controllers;

use \Input;
use \Redirect;
use \Request;
use \Session;
use \Slugify;
use \View;
use \Image;
use \File;
use \Validator;
use Ao\Data\Models\Basicdata;
use Ao\Data\Models\Notification;
use Ao\Data\Models\Gallery;

class GalleryController extends WcpController{

	public function __construct(Gallery $model)
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
			// Load model categories
			$categories = $this->model->categories();
			$catArray = [""=>"Select Category"];
			foreach($categories as $category)
			{
				$catArray[$category->id] = $category->name;
			}
			$this->viewdata["categories"] = $catArray;

			$this->viewdata["menu"] = 4;
		}
	}

	/*
	* Load a list of resources
	*/
	public function index()
	{
		// Get list of items
		$list = $this->model->orderBy("created_at", "desc")
							->with(["category", "photo"])->paginate(20);
		// Load view data
		$this->viewdata["list"] = $list;
		// Load view
		return View::make('wcp::pages.gallery.list', $this->viewdata);
	}

	/*
	* Load a specified resource
	*/
	public function show($id)
	{
		// Get list of items
		$item = $this->model->with(["category", "photos" => function($query){
			$query->orderBy("created_at", "desc")->paginate(30);
		}])->find($id);
		// Validate model
		if (count($item) < 1) {
			return \App::abort(404);
		}
		// Load view data
		$this->viewdata["item"] = $item;
		// Load view
		return View::make('wcp::pages.gallery.show', $this->viewdata);
	}

	/*
	* Load a specified resource
	*/
	public function edit($id)
	{
		// Get list of items
		$item = $this->model->with(["category", "photos" => function($query){
									$query->first();
							}])->find($id);
		// Validate model
		if (count($item) < 1) {
			return \App::abort(404);
		}
		// Load view data
		$this->viewdata["item"] = $item;
		// Load view
		return View::make('wcp::pages.gallery.edit', $this->viewdata);
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
					"title" => "required|unique:gallery",
					"category" => "required|exists:categories,id",
					"description" => "required"
		];
		// Validation Messages
		$messages = [
					"title.required" => "The album title is required.",
					"title.unique" => "That album already exists.",
					"description.required" => "The album description is required.",
					"category.required" => "Please select a category for this album.",
					"category.exists" => "The selected category was not found.",
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
			return Redirect::to('admin/gallery')->withInput();
		}
		$newData = [
			"title" => $data["title"],
			"slug" => Slugify::slugify($data["title"]),
			"description" => $data["description"],
			"category_id" => $data["category"]
		];
		// Save new data
		$this->model->create($newData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"New gallery album created."]);
		// Redirect back
		return Redirect::to('admin/gallery');
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
					"title" => "required|unique:gallery,title,".$id."",
					"category" => "required|exists:categories,id",
					"description" => "required"
		];
		// Validation Messages
		$messages = [
					"title.required" => "The album title is required.",
					"title.unique" => "That album already exists.",
					"description.required" => "The album description is required.",
					"category.required" => "Please select a category for this album.",
					"category.exists" => "The selected category was not found.",
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
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Gallery does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		// Prep update data
		$updateData = [
			"title" => $data["title"],
			"slug" => Slugify::slugify($data["title"]),
			"description" => $data["description"],
			"category_id" => $data["category"]
		];
		// Save new data
		$item->update($updateData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"Gallery album updated."]);
		// Redirect back
		return Redirect::back();
	}

	/*
	* Update an existing resource
	*/
	public function updatePhoto($id, $id2)
	{
		// Request data
		$data = Input::all();
		// Validation Rules
		$rules = [
					
		];
		// Validation Messages
		$messages = [
					
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
		$item = $this->model->with(["photo" => function($query) use($id2){
			$query->find($id2);
		}])->find($id);
		if (count($item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Gallery does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		if (count($item->photo) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Photo does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		// Prep update data
		$updateData = [
			"caption" => $data["caption"]
		];
		// Save new data
		$item->photo->update($updateData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"Photo updated."]);
		// Redirect back
		return Redirect::back();
	}

	/*
	* Upload new resource
	*/
	public function upload()
	{
		// Request data
		$data = Input::all();
		// Validation Rules
		$rules = [
					"gallery_id" => "required",
					"file" => "required|max:1200|mimes:jpeg,jpg,png"
		];
		// Validation Messages
		$messages = [
					"gallery_id.required" => "Please specify a gallery to upload to.",
					"file.required" => "Please select a file to upload.",
					"file.required" => "Please select a file to upload.",
					"file.max" => "Maximum allowed file size of 1200kb exceeded.",
					"file.mimes" => "Unsupported file type, supported file types are jpeg and png.",
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
			if (Request::ajax()){
				return \Response::json(["status"=>"danger",  "access"=>"wcp", "message"=>$message], 422);
			}
			else{
				// Flash message
				Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>$message]);
				// Redirect back
				return Redirect::back()->withInput();
			}
		}
		if (!Input::hasFile('file')) {
			if (Request::ajax()){
				return \Response::json(["status"=>"danger",  "access"=>"wcp", "message"=>"Please select a file to upload."], 422);
			}
			else{
				// Flash message
				Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Please select a file to upload."]);
				// Redirect back
				return Redirect::back()->withInput();
			}
		}
		// Find the gallery model
		$item = $this->model->find($data["gallery_id"]);
		if (count($item) < 1) {
			if (Request::ajax()){
				return \Response::json(["status"=>"danger",  "access"=>"wcp", "message"=>"The specified gallery was not found."], 422);
			}
			else{
				// Flash message
				Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"The specified gallery was not found."]);
				// Redirect back
				return Redirect::back()->withInput();
			}
		}
		$file = Input::file('file');
		$thumbnail = $file;
		$filename = $file->getClientOriginalName();
		$filename = md5($filename.time().rand(0,1000));
		$thumb_filename = md5($filename.'thumbnail');
		$ext = $file->getClientOriginalExtension();
		$path = "data/img/gallery/";
		$thumbpath = "data/img/gallery/thumbnail/";
		// Move main image to destination folder
		$file->move($path, $filename.".".$ext);
		// Move thumbnail to thumbnail destination folder
		File::copy($path.$filename.".".$ext, $thumbpath.$thumb_filename.".".$ext);
		// Prep new data
		$newData = [
			"caption" => "",
			"image" => $path.$filename.".".$ext,
			"thumbnail" => $thumbpath.$thumb_filename.".".$ext
		];
		// Save new data
		$item->photos()->create($newData);
		// Return a success response
		if (Request::ajax()){
			return \Response::json(["status"=>"success",  "access"=>"wcp", "message"=>"Success."], 200);
		}
		else{

			// Flash message
			Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"New gallery album created."]);
			// Redirect back
			return Redirect::back();
		}
	}

	/*
	* Delete an existing resource
	*/
	public function destroy($id)
	{
		$this->dodestroy($id);
		// Flash message
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Gallery deleted."]);
		// Redirect back
		return Redirect::to("admin/gallery");
	}

	/*
	* Delete an existing resource
	*/
	public function destroyPhoto($id, $id2)
	{
		$this->dodestroyphotos($id, $id2);
		// Flash message
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Photo deleted."]);
		// Redirect back
		return Redirect::to("admin/gallery/".$id);
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
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Gallery album deleted."]);
		// Redirect back
		return Redirect::to("admin/gallery");
	}


	/*
	* Bulk delete an existing resource
	*/
	public function bulkDestroyPhotos($id)
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
			$this->dodestroyphotos($id, $id2);
		};
		// Flash message
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Photos deleted."]);
		// Redirect back
		return Redirect::to("admin/gallery/".$id);
	}

	/*
	* Delete an existing resource
	*/
	public function dodestroy($id)
	{
		$item = $this->model->with("photos")->find($id);
		if (count($item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Gallery does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		if (count($item->photos) > 0) {
			foreach($item->photos as $photo){
				// Delete gallery photos
				if (File::exists($photo->image)) { File::delete($photo->image); }
				if (File::exists($photo->thumbnail)) { File::delete($photo->thumbnail); }
			}
		}
		// Delete item photos
		$item->photos()->delete();
		// Delete the item
		$item->delete();
	}

	/*
	* Delete an existing resource
	*/
	public function dodestroyphotos($id, $id2)
	{
		$item = $this->model->with(["photo"=>function($query) use($id2){
				$query->find($id2);
		}])->find($id);
		if (count($item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Gallery does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		if (count($item->photo) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Photo does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		// Delete gallery photo
		if (File::exists($item->photo->image)) { File::delete($item->photo->image); }
		if (File::exists($item->photo->thumbnail)) { File::delete($item->photo->thumbnail); }
		// Delete item photo
		$item->photo->delete();
	}
} 

