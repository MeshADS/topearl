<?php namespace Ao\Wcp\Controllers;

use \Input;
use \Redirect;
use \Session;
use \Slugify;
use \View;
use \Validator;
use Ao\Data\Models\Basicdata;
use Ao\Data\Models\Notification;
use Ao\Data\Models\Forms;
use Ao\Data\Models\Datagroups;

class FormsController extends WcpController{

	public function __construct(Forms $model)
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
			// Set element types
			$this->viewdata["elementtypes"] = \Config::get("selectoptions.elementTypes", []);
			// Set element sizes
			$this->viewdata["elementsizes"] = \Config::get("selectoptions.elementSizes", []);
		}
	}

	/*
	* Load a list of resources
	*/
	public function index()
	{
		// Get list of items
		$list = $this->model->orderBy("created_at", "desc")->with(["elements", "submitions"])->paginate(20);
		// Load view data
		$this->viewdata["list"] = $list;
		$this->viewdata["submenu"] = 2.1;
		// Load view
		return View::make('wcp::pages.forms.list', $this->viewdata);
	}

	/*
	* Load a specified resource
	*/
	public function elements($id)
	{
		// Get models
		$form = $this->model->find($id);
		$elements = $form->elements()->with("listValues")->orderBy("position")->get();
		// Load view data
		$this->viewdata["form"] = $form;
		$this->viewdata["elements"] = $elements;
		$this->viewdata["submenu"] = 2.1;
		// Load view
		return View::make('wcp::pages.forms.elements', $this->viewdata);
	}

	/*
	* Load a specified resource
	*/
	public function submitions($id)
	{
		// Grab filters
		$filter = Input::get("filter", "desc");
		$rangeFrom = Input::get("from", null);
		$rangeTo = Input::get("to", null);
		// Get models
		$form = $this->model->find($id);
		$submitions = $form->submitions()->orderBy("created_at", $filter);
		if (!is_null($rangeFrom) && strlen($rangeFrom) > 0) {
			$rangeFrom = date("Y-m-d H:i:s", strtotime($rangeFrom));
			$submitions = $submitions->where("created_at", ">=", $rangeFrom);
			if (!is_null($rangeTo) && strlen($rangeTo) > 0) {
				$rangeTo = date("Y-m-d H:i:s", strtotime($rangeTo));
				$submitions = $submitions->where("created_at", "<=", $rangeTo);
			};
		};
		$submitions = $submitions->paginate(20);
		// Load view data
		$this->viewdata["form"] = $form;
		$this->viewdata["submitions"] = $submitions;
		$this->viewdata["customFilter"] = [
											"from" => (!is_null($rangeFrom)) ? date('m/d/Y', strtotime($rangeFrom)) : '',
											"to" => (!is_null($rangeTo)) ? date('m/d/Y', strtotime($rangeTo)) : '',
											"filter" => $filter,
											];
		$this->viewdata["submenu"] = 2.1;
		// Load view
		return View::make('wcp::pages.forms.submitions', $this->viewdata);
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
					"name" => "required|unique:tprl_forms"
				];
		// Validation Messages
		$messages = [
					"name.required" => "The form name is required.",
					"name.unique" => "Form already exists."
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
		$publish = Input::get("publish", 0);
		$notify = Input::get("notify", 0);
		$newData = [
			"name" => str_replace("'", "", $data["name"]),
			"slug" => Slugify::slugify($data["name"]),
			"publish" => $publish,
			"notify" => $notify
		];
		// Save new data
		$newForm = $this->model->create($newData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"New form created."]);
		// Redirect back
		return Redirect::to('admin/forms/'.$newForm->id.'/elements');
	}

	/*
	* Save new resource
	*/
	public function createElements($form, $type)
	{
		// Request data
		$data = Input::all();
		// Validation rules and messages
		$typesValidation = \Config::get("validationRules.formElements", []);
		// Validation
		$validation = Validator::make($data, $typesValidation[$type]['rules'], $typesValidation[$type]['messages']);
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
		// Find model
		$form = $this->model->find($form);
		// Validate model
		if (count($form) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Form not found!"]);
			// Redirect back
			return Redirect::back()->withInput();
		}
		// Prep new data
		$name = Input::get("name", '');
		$rules = Input::get("rules", []);
		$list = Input::get("list", []);
		$value = Input::get("value", []);
		$newData = [
			"type" => $type,
			"name" => $name,
			"groupie" => str_replace("'", "", $data["group"]),
			"position" => $data["position"],
			"size" => $data["size"],
			"slug" =>  Slugify::slugify($name),
			"rules" => serialize($rules)
		];
		// Save new data
		$newElement = $form->elements()->create($newData);
		// Loop through list
		for ($i=0; $i < count($list); $i++) {
			// Prep new list data
			$newVal = [
				"name" => str_replace("'", "", $list[$i]),
				"slug" => Slugify::slugify($list[$i]),
				"value" => str_replace("'", "", $value[$i])
			];
			// Create new list data
			$newElement->listValues()->create($newVal);
		}
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"New form element created."]);
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
					"name" => "required|unique:tprl_forms,name,".$id
		];
		// Validation Messages
		$messages = [
					"name.required" => "The form name is required.",
					"name.unique" => "Form already exists."
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
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Form does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		// Prep new data
		$publish = Input::get("publish", 0);
		$notify = Input::get("notify", 0);
		$updateData = [
			"name" => str_replace("'", "", $data["name"]),
			"slug" => Slugify::slugify($data["name"]),
			"publish" => $publish,
			"notify" => $notify
		];
		// Save new data
		$item->update($updateData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"Form updated."]);
		// Redirect back
		return Redirect::back();
	}

	/*
	* Update an existing resource
	*/
	public function updateElement($form, $element)
	{	
		// Find model
		$form = $this->model->find($form);
		// Validate model
		if (count($form) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Form not found!"]);
			// Redirect back
			return Redirect::back();
		}
		// Find model
		$element = $form->elements()->find($element);
		// Validate model
		if (count($element) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Element not found!"]);
			// Redirect back
			return Redirect::back();
		}
		// Request data
		$data = Input::all();
		// Validation rules and messages
		$typesValidation = \Config::get("validationRules.updateFormElements", []);
		// Validation
		$validation = Validator::make($data, $typesValidation[$element->type]['rules'], $typesValidation[$element->type]['messages']);
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
		
		// Prep new data
		$name = Input::get("name", '');
		$rules = Input::get("rules", []);
		$list = Input::get("list", []);
		$value = Input::get("value", []);
		$olist = Input::get("olist", []);
		$ovalue = Input::get("ovalue", []);
		$oid = Input::get("oid", []);
		$delete = Input::get("delete", []);
		$newData = [
			"name" => $name,
			"size" => $data["size"],
			"groupie" => str_replace("'", "", $data["group"]),
			"position" => $data["position"],
			"slug" =>  Slugify::slugify($name),
			"rules" => serialize($rules)
		];
		// Save new data
		$element->update($newData);
		// Loop through list
		for ($i=0; $i < count($list); $i++) {
			// Prep new list data
			$newVal = [
				"name" => str_replace("'", "", $list[$i]),
				"slug" => Slugify::slugify($list[$i]),
				"value" => str_replace("'", "", $value[$i])
			];
			// Create new list data
			$element->listValues()->create($newVal);
		}
		// Update old list
		for ($i=0; $i < count($olist); $i++) {
			// Find list value model
			$lv = $element->listValues()->find($oid[$i]);
			// Verify wether to delete
			if ($delete[$i] > 0) {
				// Delete the list value
				$lv->delete();
			}
			else{
				// Prep list value update data
				$updateVal = [
					"name" => str_replace("'", "", $olist[$i]),
					"slug" => Slugify::slugify($olist[$i]),
					"value" => str_replace("'", "", $ovalue[$i])
				];
				// Update list data
				$lv->update($updateVal);				
			}
		}
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"Form element updated successfully."]);
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
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Form deleted."]);
		// Redirect back
		return Redirect::to("admin/forms");
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
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Forms deleted."]);
		// Redirect back
		return Redirect::to("admin/forms");
	}
	/*
	* Delete an existing resource
	*/
	public function dodestroy($id)
	{
		$item = $this->model->find($id);
		if (count($item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Form does not exist."]);
			// Redirect back
			return Redirect::back();
		}
		// Get form elements
		$elements = $item->elements()->get();
		if (count($elements) > 0) {
			$listValues = $elements->listValues()->get();
			// Delete elements
			$elements->delete();
			// Delete list values
			$listValues->delete();
		}
		// Get form submitions
		$submitions = $item->submitions()->get();
		if (count($submitions) > 0) {
			// Delete submitions
			$submitions->delete();
		}
		// Delete the item
		$item->delete();
	}
	/*
	* Delete an existing resource
	*/
	public function destroyElement($form, $element)
	{
		// Prep flash data
		$flashData = [
					"level" => "",
					"access" => "wcp",
					"type" => "page",
					"message" => ""
				];
		// Find form model
		$form = $this->model->find($form);
		// Validate form model
		if (count($form) < 1) {
			// Prep flash data
			$flashData["level"] = "danger";
			$flashData["message"] = "Form not found.";
			// Flash message
			$this->flashMessage($flashData);
			// Redirect to previous page
			return Redirect::back();
		}
		// Find form element model
		$element = $form->elements()->find($element);
		// Validate from element model
		if (count($element) < 1) {
			// Prep flash data
			$flashData["level"] = "danger";
			$flashData["message"] = "Element not found.";
			// Flash message
			$this->flashMessage($flashData);
			// Redirect to previous page
			return Redirect::back();
		}
		// Destroy the element
		$delete = $this->doDestroyElement($element);
		// Validate destroy method
		if (!$delete) {
			// Prep flash data
			$flashData["level"] = "danger";
			$flashData["message"] = "Unable to delete element, please try again shortly.";
			// Flash message
			$this->flashMessage($flashData);
			// Redirect to previous page
			return Redirect::back();
		}
		// Prep flash data
		$flashData["level"] = "success";
		$flashData["message"] = "Element successfully deleted.";
		// Flash message
		$this->flashMessage($flashData);
		// Redirect to previous page
		return Redirect::back();

	}
	/*
	* Bulk delete an existing resource
	*/
	public function bulkDestroyElements($form)
	{
		// Prep flash data
		$flashData = [
					"level" => "",
					"access" => "wcp",
					"type" => "page",
					"message" => ""
				];
		// Request data
		$data = Input::all();
		// Validation Rules
		$rules = [
					"list" => "required",
		];
		// Validation Messages
		$messages = [
					"list" => "Please select elements to delete."
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
			// Prep flash data
			$flashData["level"] = "success";
			$flashData["message"] = $message;
			// Flash message
			$this->flashMessage($flashData);
			// Redirect to previous page
			return Redirect::back();
		}
		// Find form model
		$form = $this->model->find($form);
		// Validate form model
		if (count($form) < 1) {
			// Prep flash data
			$flashData["level"] = "danger";
			$flashData["message"] = "Form not found.";
			// Flash message
			$this->flashMessage($flashData);
			// Redirect to previous page
			return Redirect::back();
		}
		// Loop through supplied list
		foreach ($data["list"] as $id) {
			// Find form element model
			$element = $form->elements()->find($id);
			// Validate from element model
			if (count($element) < 1) {
				// Prep flash data
				$flashData["level"] = "danger";
				$flashData["message"] = "Element with an id of ".$id." not found.";
				// Flash message
				$this->flashMessage($flashData);
				// Redirect to previous page
				return Redirect::back();
			}
			// Destroy the element
			$delete = $this->doDestroyElement($element);
			// Validate destroy method
			if (!$delete) {
				// Prep flash data
				$flashData["level"] = "danger";
				$flashData["message"] = "Unable to delete element (".(strlen($element->name) > 0) ? $element->name : str_replace("-", " ", ucwords($element->type)) ."), please try again shortly.";
				// Flash message
				$this->flashMessage($flashData);
				// Redirect to previous page
				return Redirect::back();
			}
		};
		// Prep flash data
		$flashData["level"] = "warning";
		$flashData["message"] = "Selected elements deleted successfully.";
		// Flash message
		$this->flashMessage($flashData);
		// Redirect to previous page
		return Redirect::back();
	}
	/*
	* Delete an existing resource
	*/
	public function doDestroyElement($element)
	{
		// Get element list value model
		$listValues = $element->listValues()->get();
		// Validate list value model
		if (count($listValues) > 0) {
			// Delete list values
			$element->listValues()->delete();
		}
		// Delete elements
		$deleted = $element->delete();
		// Return a response
		if (!$deleted) {
			return false;
		}
		return true;		
	}

	/*
	* Delete an existing resource
	*/
	public function destroySubmition($form, $submition)
	{
		// Prep flash data
		$flashData = [
					"level" => "",
					"access" => "wcp",
					"type" => "page",
					"message" => ""
				];
		// Find form model
		$form = $this->model->find($form);
		// Validate form model
		if (count($form) < 1) {
			// Prep flash data
			$flashData["level"] = "danger";
			$flashData["message"] = "Form not found.";
			// Flash message
			$this->flashMessage($flashData);
			// Redirect to previous page
			return Redirect::back();
		}
		// Find form submition model
		$submition = $form->submitions()->find($submition);
		// Validate from submition model
		if (count($submition) < 1) {
			// Prep flash data
			$flashData["level"] = "danger";
			$flashData["message"] = "Submition not found.";
			// Flash message
			$this->flashMessage($flashData);
			// Redirect to previous page
			return Redirect::back();
		}
		// Destroy the submition
		$delete = $this->doDestroySubmition($submition);
		// Validate destroy method
		if (!$delete) {
			// Prep flash data
			$flashData["level"] = "danger";
			$flashData["message"] = "Unable to delete submition, please try again shortly.";
			// Flash message
			$this->flashMessage($flashData);
			// Redirect to previous page
			return Redirect::back();
		}
		// Prep flash data
		$flashData["level"] = "success";
		$flashData["message"] = "Submition successfully deleted.";
		// Flash message
		$this->flashMessage($flashData);
		// Redirect to previous page
		return Redirect::back();

	}
	/*
	* Bulk delete an existing resource
	*/
	public function bulkDestroySubmitions($form)
	{
		// Prep flash data
		$flashData = [
					"level" => "",
					"access" => "wcp",
					"type" => "page",
					"message" => ""
				];
		// Request data
		$data = Input::all();
		// Validation Rules
		$rules = [
					"list" => "required",
		];
		// Validation Messages
		$messages = [
					"list" => "Please select submitions to delete."
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
			// Prep flash data
			$flashData["level"] = "success";
			$flashData["message"] = $message;
			// Flash message
			$this->flashMessage($flashData);
			// Redirect to previous page
			return Redirect::back();
		}
		// Find form model
		$form = $this->model->find($form);
		// Validate form model
		if (count($form) < 1) {
			// Prep flash data
			$flashData["level"] = "danger";
			$flashData["message"] = "Form not found.";
			// Flash message
			$this->flashMessage($flashData);
			// Redirect to previous page
			return Redirect::back();
		}
		// Loop through supplied list
		foreach ($data["list"] as $id) {
			// Find form submition model
			$submition = $form->submitions()->find($id);
			// Validate from submition model
			if (count($submition) < 1) {
				// Prep flash data
				$flashData["level"] = "danger";
				$flashData["message"] = "Submition with an id of ".$id." not found.";
				// Flash message
				$this->flashMessage($flashData);
				// Redirect to previous page
				return Redirect::back();
			}
			// Destroy the submition
			$delete = $this->doDestroySubmition($submition);
			// Validate destroy method
			if (!$delete) {
				// Prep flash data
				$flashData["level"] = "danger";
				$flashData["message"] = "Unable to delete submition (".(strlen($submition->name) > 0) ? $submition->name : str_replace("-", " ", ucwords($submition->type)) ."), please try again shortly.";
				// Flash message
				$this->flashMessage($flashData);
				// Redirect to previous page
				return Redirect::back();
			}
		};
		// Prep flash data
		$flashData["level"] = "warning";
		$flashData["message"] = "Selected submitions deleted successfully.";
		// Flash message
		$this->flashMessage($flashData);
		// Redirect to previous page
		return Redirect::back();
	}
	/*
	* Delete an existing resource
	*/
	public function doDestroySubmition($submition)
	{
		// Delete submitions
		$deleted = $submition->delete();
		// Return a response
		if (!$deleted) {
			return false;
		}
		return true;		
	}
} 

