<?php namespace Ao\Wcp\Controllers;

use \Input;
use \Redirect;
use \Session;
use \Slugify;
use \View;
use \Validator;
use \Sentry;
use Ao\Wcp\Acme\BSGateway;
use Ao\Data\Models\Basicdata;
use Ao\Data\Models\Notification;
use Ao\Data\Models\User;

class AccountsController extends WcpController{

	public function __construct(User $model)
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
			// Load controller model
			$this->model = $model;
			// Load groups
			$groups = Sentry::findAllGroups();
			$groupsArr = [
				"Select Group"
			];
			foreach($groups as $group)
			{
				$groupsArr[$group->id] = $group->name;
			}
			$this->viewdata["groups"] = $groupsArr;

			$this->viewdata["menu"] = 8;
		}
	}

	/*
	* Load a list of resources
	*/
	public function index()
	{
		// Get list of items
		$list = $this->model->with(['groups',"phonenumbers", "phone"])->paginate(20);
		// Load view data
		$this->viewdata["list"] = $list;
		// Load view
		return View::make('wcp::pages.accounts.list', $this->viewdata);
	}

	/*
	* Load a specified resource
	*/
	public function show($id)
	{
		// Get list of items
		$item = $this->model->with(["programs"=>function($query){
			$query->orderBy("position", "asc")
				  ->with("type")
				  ->get();
		}, "awards", "phonenumbers", "phone", "results"=>function($query){
				$query->with(["program", "semester", "resultslist" => function($query2){
					$query2->orderBy("position", "asc")->get();
				} ])->get();
		}])->find($id);
		// Get person group
		$group = $item->groups()->first();
		$item->group = $group;
		// Get all programs
		$programs = $this->model->getPrograms();
		// Get all semesters
		$semesters = $this->model->getSemesters();
		// Load view data
		$this->viewdata["item"] = $item;
		$this->viewdata["programs"] = $programs;
		$this->viewdata["semesters"] = $semesters;
		// Load view
		return View::make('wcp::pages.accounts.profile', $this->viewdata);
	}

	/*
	* Load form to create a new resource resources
	*/
	public function create()
	{
		// Load view
		return View::make('wcp::pages.accounts.create', $this->viewdata);
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
			"first_name" => "required",
			"last_name" => "required",
			"email" => "required|email",
			"password" => "required|min:8|confirmed",
			"password_confirmation" => "required_with:password"
		];
		// Validation Messages
		$messages = [
			"first_name.required" => "Please enter a first name.",
			"last_name.required" => "Please enter a last name.",
			"email.required" => "Please enter an email.",
			"email.email" => "Please enter a valid email.",
			"password.required" => "Please enter a password.",
			"password.min" => "Password must have atleast 8 characters.",
			"password.confirmed" => "Confirmation password does not match password.",
			"password_confirmation.required_with" => "Please confirm password.",
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
			return Redirect::to('admin/accounts/create')->withInput();
		}
		// Get group
		$group = Sentry::findGroupById($data["group"]);
		// Get permissions
		$permissions = \Config::get("permissions.".str_replace(" ", "_", $group->name));
		$activate = Input::get("activate", false);
		$activate = ($activate === false) ? false : true;
		// Prep new data
		$newData = [
			"first_name" => $data["first_name"],
			"last_name" => $data["last_name"],
			"email" => $data["email"],
			"password" => $data["password"],
			"permissions" => $permissions,
			"activated" => $activate
		];

		try
		{
		    // Create the user
		    $user = Sentry::createUser($newData);

		    // Find the group using the group id
		    $userGroup = Sentry::findGroupById($data["group"]);

		    // Assign the group to the user
		    $user->addGroup($userGroup);

			// Flash message
			Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"New Account created."]);
			// Redirect back
			return Redirect::to('admin/accounts');
		}
		catch (\Cartalyst\Sentry\Users\UserExistsException $e)
		{
		    // Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Account already exists."]);
			// Redirect back
			return Redirect::to('admin/accounts/create')->withInput();
		}
		catch (\Cartalyst\Sentry\Groups\GroupNotFoundException $e)
		{
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Group does not exist."]);
			// Redirect back
			return Redirect::to('admin/accounts/create')->withInput();
		}
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
			"first_name" => "required",
			"last_name" => "required",
			"email" => "required|email",
			"phone_id" => "exists:tprl_phonenumbers,id",
			"group" => "required|exists:tprl_groups,id",
		];
		// Validation Messages
		$messages = [
			"first_name.required" => "Please enter a first name.",
			"last_name.required" => "Please enter a last name.",
			"email.required" => "Please enter an email.",
			"email.email" => "Please enter a valid email.",
			"group.required" => "Please select a group.",
			"group.exists" => "Selected group was not found.",
		];
		// Validation
		$validation = Validator::make($data, $rules, $messages);
		// Check validation
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
		// find model
		$item = $this->model->with("groups")->find($id);
		if (count($item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Account was not found."]);
			// Redirect back
			return Redirect::back();
		}
		// Find group
		$group = Sentry::findGroupById($data["group"]);
		// Get permissions
		$permissions = \Config::get("permissions.".str_replace(" ", "_", $group->name));
		// Prep new data
		try
		{
		    // Find the user using the user id
		    $user = Sentry::findUserById($id);

		    // Update the user details
		    $user->first_name = $data["first_name"];
		    $user->last_name = $data["last_name"];
		    $user->email = $data["email"];
		    $user->phone_id = $data["phone_id"];
		    $user->permissions = $permissions;

		    // Update the user
		    if ($user->save())
		    {
		        // User information was updated
		        // Remove old group
				$item->groups()->detach();
				// Attach new group
				$item->groups()->attach($data["group"]);
				// Flash message
				Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"Account successfully updated."]);
				// Redirect back
				return Redirect::back();
		    }
		    else
		    {
		        // User information was not updated
		        // Flash message
				Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Account failed to update, please try again later."]);
				// Redirect back
				return Redirect::back();
		    }
		}
		catch (Cartalyst\Sentry\Users\UserExistsException $e)
		{
		    // Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"An account bearing that email address already exists."]);
			// Redirect back
			return Redirect::back();
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Account was not found."]);
			// Redirect back
			return Redirect::back();
		}

	}

	/*
	* Update an existing resource
	*/
	public function change_password($id)
	{
		// Request data
		$data = Input::all();
		// Validation Rules
		$rules = [
			"password" => "required|min:8|confirmed",
			"password_confirmation" => "required_with:password"
		];
		// Validation Messages
		$messages = [
			"password.required" => "Please enter a password.",
			"password.min" => "Password must have atleast 8 characters.",
			"password.confirmed" => "Confirmation password does not match password.",
			"password_confirmation.required_with" => "Please confirm password.",
		];
		// Validation
		$validation = Validator::make($data, $rules, $messages);
		// Check validation
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
		try
		{
		    // Find the user by the user's ID
		    $user = Sentry::findUserById($id);

		    // Get the password reset code
		    $resetCode = $user->getResetPasswordCode();

		    // Attempt to reset the user password
	        if ($user->attemptResetPassword($resetCode, $data["password"]))
	        {
	        	// Password reset passed
	            // Flash message
				Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"Password was successfully changed."]);
				// Redirect back
				return Redirect::back();
	        }
	        else
	        {
	            // Password reset failed
	            // Flash message
				Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Password reset faile, please try again later."]);
				// Redirect back
				return Redirect::back();
	        }
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		   // Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Account was not found."]);
			// Redirect back
			return Redirect::back();
		}
	}

	/*
	* Toggle activation for an existing resource
	*/
	public function activate($id)
	{
		try
		{
		    // Find the user by the user's ID
		    $user = Sentry::findUserById($id);
		    // Let's get the activation code
		    $activationCode = $user->getActivationCode();
		     // Attempt to activate the user
		    if ($user->attemptActivation($activationCode))
		    {
		        // User activation passed
		        // Flash message
				Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"Account successfully activated."]);
				// Redirect back
				return Redirect::back();
		    }
		    else
		    {
		        // User activation failed
		        // Flash message
				Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Account activation failed, please try again later."]);
				// Redirect back
				return Redirect::back();
		    }
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		   	// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Account was not found."]);
			// Redirect back
			return Redirect::back();
		}
		catch (Cartalyst\Sentry\Users\UserAlreadyActivatedException $e)
		{
		    // Flash message
			Session::flash("system_message", ["level"=>"info",  "access"=>"wcp", "type"=>"page", "message"=>"Account is already activated."]);
			// Redirect back
			return Redirect::back();
		}
	}

	/*
	* Save new resource
	*/
	public function message($id)
	{
		// Request data
		$data = Input::all();
		// Validation Rules
		$rules = [
			"sender_id" => "required|exists:tprl_users,id",
			"body" => "required",
		];
		// Validation Messages
		$messages = [
			"body.required" => "You can't send an empty message!",
			"sender_id.required" => "Something went wrong, please try again.",
			"sender_id.exists" => "Couldn't identify you, please login again and retry.",
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
				for ($i=0; $i < count($m) ; $i++) { 
					$message .= $m[$i]."<br>";
				}
			}
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>$message]);
			// Redirect back
			return Redirect::back();
		}
		// Find requested model
		$item = $this->model->find($id);
		$newData = [
			"sender_id" => $data["sender_id"],
			"user_id" => $id,
			"body" => $data["body"],
		];
		// Create new message
		$item->messages()->create($newData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"Message successfully sent!."]);
		// Redirect back
		return Redirect::back();
	}

	/*
	* Send an email
	*/
	public function sendmail($id)
	{
		// Request data
		$data = Input::all();
		// Validation Rules
		$rules = [
			"sender_id" => "required|exists:tprl_users,id",
			"body" => "required",
			"subject" => "required",
		];
		// Validation Messages
		$messages = [
			"body.required" => "You can't send an empty message!",
			"subject.required" => "Please enter a subject for the mail message!",
			"sender_id.required" => "Something went wrong, please try again.",
			"sender_id.exists" => "Couldn't identify you, please login again and retry.",
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
				for ($i=0; $i < count($m) ; $i++) { 
					$message .= $m[$i]."<br>";
				}
			}
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>$message]);
			// Redirect back
			return Redirect::back()->withInput();
		}
		// Find requested model
		$data["to"] = $this->model->find($id);
		$data["sender"] = $this->model->find($data["sender_id"]);
		$messageData = [
			"body" => $data["body"],
			"subject" => $data["subject"],
			"basicdata" => $this->viewdata["basic_info"]
		];
		// Send message
		\Mail::later(10, '_emails.basic', $messageData, function($message) use($data){
			$message->from($data["sender"]->email, $data["sender"]->first_name." ".$data["sender"]->last_name)
					->to($data["to"]->email, $data["to"]->first_name." ".$data["to"]->last_name)
					->subject($data["subject"]);
		});
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"Message successfully sent!."]);
		// Redirect back
		return Redirect::back()->withInput();
	}

	/*
	* Send a text message
	*/
	public function sendtext($id)
	{
		// Request data
		$data = Input::all();
		// Validation Rules
		$rules = [
			"sender_id" => "required|exists:tprl_users,id",
			"body" => "required",
		];
		// Validation Messages
		$messages = [
			"body.required" => "You can't send an empty message!",
			"sender_id.required" => "Something went wrong, please try again.",
			"sender_id.exists" => "Couldn't identify you, please login again and retry.",
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
				for ($i=0; $i < count($m) ; $i++) { 
					$message .= $m[$i]."<br>";
				}
			}
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>$message]);
			// Redirect back
			return Redirect::back();
		}
		// // Find requested model
		// $item = $this->model->find($id);
		// $sender = $this->model->find($data["sender_id"]);
		// $smsgatewaycredentials = \Config::get("service");
		// // New gateway
		// $gateway = new BSGateway;
		// $gateway->
		// // Flash message
		// Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"Message successfully sent!."]);
		// // Redirect back
		// return Redirect::back();
	}

	/*
	* Save new resource
	*/
	public function phonenumber($id)
	{
		// Request data
		$data = Input::all();
		// Validation Rules
		$rules = [
			"name" => "required",
			"number" => "required",
		];
		// Validation Messages
		$messages = [
			"name.required" => "Please enter a name for the new number.",
			"number.required" => "Please enter the new number.",
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
				for ($i=0; $i < count($m) ; $i++) { 
					$message .= $m[$i]."<br>";
				}
			}
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>$message]);
			// Redirect back
			return Redirect::back()->withInput();
		}
		// Find requested model
		$item = $this->model->find($id);
		// Validate user program relationship
		$exists = $item->phonenumbers()->where("number", $data["number"])->first();
		if ($exists) {
			// Flash message
			Session::flash("system_message", ["level"=>"info",  "access"=>"wcp", "type"=>"page", "message"=>"User already has that phone number."]);
			// Redirect back
			return Redirect::back()->withInput();
		}
		// Prep new data
		$makePrimary = Input::get("make_primary", null);
		$newData = [
			"name" => $data["name"],
			"number" => $data["number"],
		];
		// Save new data
		$newPhone = $item->phonenumbers()->create($newData);
		// Check if make make primary
		if (!is_null($makePrimary)) {
			$item->update(["phone_id"=>$newPhone->id]);
		}
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"New number added successfully."]);
		// Redirect back
		return Redirect::back();
	}

	/*
	* Update existing resource
	*/
	public function updatePhone($id, $phone)
	{
		// Request data
		$data = Input::all();
		// Validation Rules
		$rules = [
			"name" => "required",
			"number" => "required",
		];
		// Validation Messages
		$messages = [
			"name.required" => "Please enter a name for the new number.",
			"number.required" => "Please enter the new number.",
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
				for ($i=0; $i < count($m) ; $i++) { 
					$message .= $m[$i]."<br>";
				}
			}
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>$message]);
			// Redirect back
			return Redirect::back();
		}
		// Find requested model
		$item = $this->model->find($id);
		// Validate user program relationship
		$phone = $item->phonenumbers()->find($phone);
		if (!$phone) {
			// Flash message
			Session::flash("system_message", ["level"=>"info",  "access"=>"wcp", "type"=>"page", "message"=>"Phone number not found."]);
			// Redirect back
			return Redirect::back();
		}
		// Prep update data
		$makePrimary = Input::get("make_primary", null);
		$updateData = [
			"name" => $data["name"],
			"number" => $data["number"],
		];
		// Save update data
		$phone->update($updateData);
		// Check if make make primary
		if (!is_null($makePrimary)) {
			$item->update(["phone_id"=>$phone->id]);
		}
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"Phone number successfully updated."]);
		// Redirect back
		return Redirect::back();
	}

	public function destroyPhone($id, $phone)
	{
		$item = $this->model->find($id);
		if (!$item) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"User not found."]);
			// Redirect back
			return Redirect::to("admin/accounts");
		}
		$phone = $item->phonenumbers()->find($phone);
		if (!$phone) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Phone number not found."]);
			// Redirect back
			return Redirect::to("admin/accounts/".$id);
		}
		// Delete
		$phone->delete();
		// Flash message
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Phone number deleted."]);
		// Redirect back
		return Redirect::to("admin/accounts/".$id);
	}

	/*
	* Save new resource
	*/
	public function addToPrograms($id)
	{
		// Request data
		$data = Input::all();
		// Validation Rules
		$rules = [
			"program_id" => "required|exists:tprl_programs,id",
		];
		// Validation Messages
		$messages = [
			"program_id.required" => "Please select a program.",
			"program_id.exists" => "The selected program does not exist.",
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
				for ($i=0; $i < count($m) ; $i++) { 
					$message .= $m[$i]."<br>";
				}
			}
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>$message]);
			// Redirect back
			return Redirect::back();
		}
		// Find requested model
		$item = $this->model->find($id);
		// Validate user program relationship
		$exists = $item->programs()->find($data["program_id"]);
		if ($exists) {
			// Flash message
			Session::flash("system_message", ["level"=>"info",  "access"=>"wcp", "type"=>"page", "message"=>"User is already part of the selected program."]);
			// Redirect back
			return Redirect::back();
		}
		// Attach program to
		$item->programs()->attach($data["program_id"]);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"User added to program successfully."]);
		// Redirect back
		return Redirect::back();
	}

	/*
	* Delete an existing resource
	*/
	public function removeProgram($id, $program)
	{
		// Find requested model
		$item = $this->model->find($id);
		// Validate user program relationship
		$exists = $item->programs()->find($program);
		if (!$exists) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Program not found."]);
			// Redirect back
			return Redirect::back();
		}
		// Attach program to
		$item->programs()->detach($program);
		// Flash message
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Program removed."]);
		// Redirect back
		return Redirect::back();
	}

	/*
	* Save new resource
	*/
	public function award($id)
	{
		// Request data
		$data = Input::all();
		// Validation Rules
		$rules = [
			"title" => "required",
			"file" => "required|mimes:pdf,x-pdf,vnd.pdf|max:1200",
			"year" => "required",
			"program_id" => "required|exists:tprl_programs,id",
		];
		// Validation Messages
		$messages = [
			"title.required" => "Please enter a title for this award.",
			"year.required" => "Please select a year for this award.",
			"program_id.required" => "Please select a program.",
			"program_id.exists" => "The selected program does not exist.",
			"file.required" => "Please select a file to upload.",
			"file.mimes" => "Invalid file, file must be PDF.",
			"file.max" => "Maximum file size of 1200kb exceeded.",
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
				for ($i=0; $i < count($m) ; $i++) { 
					$message .= $m[$i]."<br>";
				}
			}
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>$message]);
			// Redirect back
			return Redirect::back();
		}
		// Find requested model
		$item = $this->model->find($id);
		if (!$item) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"User not found."]);
			// Redirect back
			return Redirect::back();
		}
		// Prep new data
		$newData = [
					"title" => $data["title"],
					"year" => $data["year"],
					"program_id" => $data["program_id"],
				];
		// Upload file
		$file = Input::file("file");
		$ext = $file->getClientOriginalExtension();
		$path = "data/files/";
		$filename = md5(time().$file->getClientOriginalname());
		$file->move($path, $filename.".".$ext);
		// Update new data
		$newData["file"] = $path.$filename.".".$ext;
		// Save new data
		$item->awards()->create($newData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"Award published successfully."]);
		// Redirect back
		return Redirect::back();
	}

	/*
	* Update existing resource
	*/
	public function updateAward($id, $award)
	{
		// Request data
		$data = Input::all();
		// Validation Rules
		$rules = [
			"title" => "required",
			"file" => "mimes:pdf,x-pdf,vnd.pdf|max:1200",
			"year" => "required",
			"program_id" => "required|exists:tprl_programs,id",
		];
		// Validation Messages
		$messages = [
			"title.required" => "Please enter a title for this award.",
			"year.required" => "Please select a year for this award.",
			"program_id.required" => "Please select a program.",
			"program_id.exists" => "The selected program does not exist.",
			"file.mimes" => "Invalid file, file must be PDF.",
			"file.max" => "Maximum file size of 1200kb exceeded.",
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
				for ($i=0; $i < count($m) ; $i++) { 
					$message .= $m[$i]."<br>";
				}
			}
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>$message]);
			// Redirect back
			return Redirect::back();
		}
		// Find requested model
		$item = $this->model->find($id);
		if (!$item) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"User not found."]);
			// Redirect back
			return Redirect::back();
		}
		$award = $item->awards()->find($award);
		if (!$award) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Award not found."]);
			// Redirect back
			return Redirect::back();
		}
		// Prep update data
		$updateData = [
					"title" => $data["title"],
					"year" => $data["year"],
					"program_id" => $data["program_id"],
				];
		if (\Input::hasFile("file")) {
			// Upload file
			$file = Input::file("file");
			$ext = $file->getClientOriginalExtension();
			$path = "data/files/";
			$filename = md5(time().$file->getClientOriginalname());
			$file->move($path, $filename.".".$ext);
			// Update update data
			$updateData["file"] = $path.$filename.".".$ext;			
			// Delete existing file
			if (\File::exists($award->file)) {
				\File::delete($award->file);
			}
		}
		// Save update data
		$award->update($updateData);
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"Award updated successfully."]);
		// Redirect back
		return Redirect::back();
	}

	/*
	* Save new resource
	*/
	public function result($id)
	{
		// Request data
		$data = Input::all();
		// Validation Rules
		$rules = [
			"program_id" => "required|exists:tprl_programs,id",
			"semester_id" => "required|exists:tprl_datagroups,id",
			"year" => "required",
		];
		// Validation Messages
		$messages = [
			"program_id.required" => "Please select a program.",
			"program_id.exists" => "The selected program does not exist.",
			"semester_id.required" => "Please select a semester.",
			"semester_id.exists" => "The selected semester does not exist.",
			"year.required" => "Please select year.",
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
					$message .= $m[$i]."<br>";
				}
			}
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>$message]);
			// Redirect back
			return Redirect::back()->withInput();
		}
		// Find model
		$item = $this->model->find($id);
		// Validate model
		if (count($item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"User not found!"]);
			// Redirect back
			return Redirect::back()->withInput();
		}
		// Prep new data
		$list = Input::get("list", []);
		$value = Input::get("value", []);
		$position = Input::get("position", []);
		$newData = [
			"year" => $data["year"],
			"program_id" => $data["program_id"],
			"semester_id" => $data["semester_id"]
		];
		// Save new data
		$newResult = $item->results()->create($newData);
		// Loop through list
		for ($i=0; $i < count($list); $i++) {
			// Prep new list data
			$newVal = [
				"name" =>  $list[$i],
				"value" => $value[$i],
				"position" => $position[$i],
			];
			// Create new list data
			$newResult->resultslist()->create($newVal);
		}
		// Flash message
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"New result published."]);
		// Redirect back
		return Redirect::back();
	}

	/*
	* Update an existing resource
	*/
	public function updateResult($id, $result)
	{
		// Request data
		$data = Input::all();
		// Validation Rules
		$rules = [
			"program_id" => "required|exists:tprl_programs,id",
			"semester_id" => "required|exists:tprl_datagroups,id",
			"year" => "required",
		];
		// Validation Messages
		$messages = [
			"program_id.required" => "Please select a program.",
			"program_id.exists" => "The selected program does not exist.",
			"semester_id.required" => "Please select a semester.",
			"semester_id.exists" => "The selected semester does not exist.",
			"year.required" => "Please select year.",
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
					$message .= $m[$id]."<br>";
				}
			}
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>$message]);
			// Redirect back
			return Redirect::back();
		}
		// Find model
		$item = $this->model->find($id);
		// Validate model
		if (count($item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"User not found!"]);
			// Redirect back
			return Redirect::back();
		}
		// Find model
		$result = $item->results()->find($result);
		// Validate model
		if (count($result) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Result not found!"]);
			// Redirect back
			return Redirect::back();
		}
		// Prep update data
		$list = Input::get("list", []);
		$value = Input::get("value", []);
		$position = Input::get("position", []);
		$olist = Input::get("olist", []);
		$ovalue = Input::get("ovalue", []);
		$oposition = Input::get("oposition", []);
		$oid = Input::get("oid", []);
		$delete = Input::get("delete", []);
		$updateData = [
			"year" => $data["year"],
			"program_id" => $data["program_id"],
			"semester_id" => $data["semester_id"]
		];
		// Save update data
		$result->update($updateData);
		// Loop through list
		for ($i=0; $i < count($list); $i++) {
			// Prep update list data
			$newVal = [
				"name" =>  $list[$i],
				"value" => $value[$i],
				"position" => $position[$i],
			];
			// Create update list data
			$result->resultslist()->create($newVal);
		}
		// Update old list
		for ($i=0; $i < count($olist); $i++) {
			// Find list value model
			$lv = $result->resultslist()->find($oid[$i]);
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
		Session::flash("system_message", ["level"=>"success",  "access"=>"wcp", "type"=>"page", "message"=>"Result updated successfully."]);
		// Redirect back
		return Redirect::back();
	}

	/*
	* Delete an existing resource
	*/
	public function destroyResult($id, $result)
	{
		$item = $this->model->find($id);
		if (!$item) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"User not found."]);
			// Redirect back
			return Redirect::to("admin/accounts");
		}
		$result = $item->results()->find($result);
		if (!$result) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Result not found."]);
			// Redirect back
			return Redirect::to("admin/accounts");
		}
		// Delete result list
		$result->resultslist()->delete();
		// Delete
		$result->delete();
		// Flash message
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Result deleted."]);
		// Redirect back
		return Redirect::to("admin/accounts/".$id);
	}

	/*
	* Delete an existing resource
	*/
	public function destroyAward($id, $award)
	{
		$item = $this->model->find($id);
		if (!$item) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"User not found."]);
			// Redirect back
			return Redirect::to("admin/accounts");
		}
		$award = $item->awards()->find($award);
		if (!$award) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Award not found."]);
			// Redirect back
			return Redirect::to("admin/accounts");
		}
		// Delete file
		if (\File::exists($award->file)) {
			\File::delete($award->file);
		}
		// Delete
		$award->delete();
		// Flash message
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Award deleted."]);
		// Redirect back
		return Redirect::to("admin/accounts/".$id);
	}

	/*
	* Delete an existing resource
	*/
	public function destroy($id)
	{
		$this->dodestroy($id);
		// Flash message
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Account deleted."]);
		// Redirect back
		return Redirect::to("admin/accounts");
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
		Session::flash("system_message", ["level"=>"warning",  "access"=>"wcp", "type"=>"page", "message"=>"Selected accounts successfully deleted."]);
		// Redirect back
		return Redirect::to("admin/accounts");
	}

	/*
	* Delete an existing resource
	*/
	public function dodestroy($id)
	{
		$user = Sentry::getUser();
		$item = $this->model->with("groups")->find($id);
		if ($item->email == $user->email) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"You can't delete your account."]);
			// Redirect back
			return Redirect::back();
		}
		if (count($item) < 1) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"wcp", "type"=>"page", "message"=>"Account not exist."]);
			// Redirect back
			return Redirect::back();
		}
		// Detach from group
		$item->groups()->detach();		
		// Delete the item
		$item->delete();
	}
} 

