<?php namespace Ao\Wcp\Controllers;

use \Input;
use \Redirect;
use \Session;
use \Slugify;
use \Image;
use \File;
use \View;
use \Validator;
use Ao\Data\Models\Basicdata;
use Ao\Data\Models\Notification;
use Ao\Data\Models\Contentdata;

class ContentdataController extends WcpController{

	public function __construct(Contentdata $model)
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
			$this->viewdata["selectoptions"] = \Config::get("selectoptions.contentdata");
			// Set controller model
			$this->model = $model;

			$this->viewdata["menu"] = 2;

			$dataOptions = $this->datagroupOptions();

			$this->viewdata["pages"] = $dataOptions["page"];
		}
	}

	/*
	* Load a list of resources
	*/
	public function index()
	{
		// Get list of items
		$list = $this->model->orderBy("created_at", "desc")->with("page")->paginate(20);
		// Load view data
		$this->viewdata["list"] = $list;
		$this->viewdata["submenu"] = 2.8;
		// Load view
		return View::make('wcp::pages.contentdata.list', $this->viewdata);
	}

	/*
	* Load a specified resource
	*/
	public function show($id)
	{
		// Get list of items
		$item = $this->model->with("page")->find($id);
		// Validate model
		if (count($item)<1) { return \App::abort(); }
		// Load view data
		$this->viewdata["item"] = $item;
		$this->viewdata["submenu"] = 2.8;
		// Load view
		return View::make('wcp::pages.contentdata.show', $this->viewdata);
	}

	/*
	* Load a specified resource
	*/
	public function edit($id)
	{
		// Get list of items
		$item = $this->model->with("page")->find($id);
		// Validate model
		if (count($item)<1) { return \App::abort(); }
		// Load view data
		$this->viewdata["item"] = $item;
		$this->viewdata["submenu"] = 2.8;
		// Load view
		return View::make('wcp::pages.contentdata.edit', $this->viewdata);
	}

	/*
	* Load a specified resource
	*/
	public function create()
	{
		$this->viewdata["submenu"] = 2.8;
		// Load view
		return View::make('wcp::pages.contentdata.create', $this->viewdata);
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
					"page_id" => "required",
					"body" => "required",
		];
		// Validation Messages
		$messages = [
					"page_id.required" => "The content page is required.",
					"body.required" => "The content body is required.",
					"title.required" => "The content title is required.",
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
			return Redirect::to('admin/content_data/create')->withInput();
		}
		// Find item with same title and same page
		$item = $this->model->where("page_id", $data["page_id"])->where("title", $data["title"])->first();
		// Validate
		if (count($item) > 0) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"That data already exists."]);
			// Redirect back
			return Redirect::to('admin/content_data/create')->withInput();
		}
		// Prep new data
		$newData = [
			"page_id" => $data["page_id"],
			"title" => $data["title"],
			"slug" => Slugify::slugify($data["title"]),
			"body" => $data["body"]
		];
		// Save new data
		$new = $this->model->create($newData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"New content data created."]);
		// Redirect back
		return Redirect::to('admin/content_data/'.$new->id);
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
					"page_id" => "required",
					"body" => "required",
		];
		// Validation Messages
		$messages = [
					"page_id.required" => "The content page is required.",
					"body.required" => "The content body is required.",
					"title.required" => "The content title is required.",
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
		// Find item with same title and same page
		$item = $this->model->where("page_id", $data["page_id"])->where("title", $data["title"])->where("id", "!=", $id)->first();
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
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Data does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		// Prep new data
		$updateData = [
			"page_id" => $data["page_id"],
			"title" => $data["title"],
			"slug" => Slugify::slugify($data["title"]),
			"body" => $data["body"]
		];
		// Save new data
		$item->update($updateData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"Content data updated."]);
		// Redirect back
		return Redirect::back();
	}

	/*
	* Delete an existing resource
	*/
	public function destroy($id)
	{
		$return = Input::get('return', 'content_data');
		$this->dodestroy($id);
		// Flash message
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Data deleted."]);
		// Redirect back
		return Redirect::to($return);
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
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Data deleted."]);
		// Redirect back
		return Redirect::to("admin/content_data");
	}

	/*
	* Delete an existing resource
	*/
	public function dodestroy($id)
	{
		$item = $this->model->find($id);
		if (count($item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Data does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		// Delete the item
		$item->delete();
	}
} 

