<?php
class SettingsController extends SiteController {

	public function __construct()
	{
		Parent::__construct();
	}

	public function index()
	{
		$page = $this->getPage("my-account", "page");
		$group = $this->getPage("my-account", "group");
		$sections = $group->images()->orderBy("order", "asc")->get();
		$header = $page->headers()->orderBy("order", "asc")->first();
		// Get page images
		$images = ($group) ? $group->images()->get() : [];
		$imagesArr = [];
		foreach($images as $image){
			$imagesArr[$image->title] = $image;
		};
		$this->viewdata["pageImages"] = $imagesArr;
		// Prep view data
		$this->viewdata["current_menu"] = 6;
		$this->viewdata["header"] = $header;
		$this->viewdata["pageslug"] = $page->slug;
		$this->viewdata["page"] = $page;
		$this->viewdata["pagedata"] = $this->getContentdata($page->slug);
		// Serve view
		return View::make('pages.myaccount.settings', $this->viewdata);	
	}

	public function updateInfo()
	{
		// Request data
		$data = Input::all();
		// Validation Rules
		$rules = [
			"first_name" => "required",
			"last_name" => "required",
			"phone_id" => "required|exists:tprl_phonenumbers,id",
		];
		// Validation Messages
		$messages = [
			"first_name.required" => "Please enter a first name.",
			"last_name.required" => "Please enter a last name.",
			"phone_id.required" => "Please select a primary phone.",
			"phone_id.exists" => "The primary phone you selected does not exist.",
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
				for ($i=0; $i < count($m); $i++) { 
					$message .= "<i class='fa fa-ban'></i>&nbsp;".$m[$i]."<br>";
				}
			}
			// Flash message
			Session::flash("system_message", ["level"=>"danger", "type"=>"settings.basicInfo", "access"=>"site", "message"=>$message]);
			// Redirect back
			return Redirect::back();
		}
		// Find model
		$id = $this->viewdata["userdata"]->id;
		// Prep new data
		try
		{
		    // Find the user using the user id
		    $user = Sentry::findUserById($id);

		    // Update the user details
		    $user->first_name = $data["first_name"];
		    $user->last_name = $data["last_name"];
		    $user->phone_id = $data["phone_id"];

		    // Update the user
		    if ($user->save())
		    {
		        // User information was updated
				// Flash message
				Session::flash("system_message", ["level"=>"success",  "access"=>"site", "type"=>"settings.basicInfo", "message"=>"Basic info successfully updated."]);
				// Redirect back
				return Redirect::back();
		    }
		    else
		    {
		        // User information was not updated
		        // Flash message
				Session::flash("system_message", ["level"=>"danger",  "access"=>"site", "type"=>"settings.basicInfo", "message"=>"Update failed, please try again later."]);
				// Redirect back
				return Redirect::back();
		    }
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    Session::flash("system_message", ["level"=>"danger",  "access"=>"site", "type"=>"settings.basicInfo", "message"=>"Could not identify you, please login again and retry."]);
			// Redirect back
			return Redirect::back();
		}

	}

	public function changeEmail()
	{
		// Get user ID
		$id = $this->viewdata["userdata"]->id;
		// Request data
		$data = Input::all();
		// Validation Rules
		$rules = [
			"current_email" => "required",
			"current_password" => "required",
			"new_email" => "required|unique:tprl_users,email,".$id,
		];
		// Validation Messages
		$messages = [
			"current_email.required" => "Please enter your current email address.",
			"current_password.required" => "Your password is required.",
			"new_email.required" => "Please enter your new email address.",
			"new_email.unique" => "That email address is already in use.",
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
				for ($i=0; $i < count($m); $i++) { 
					$message .= "<i class='fa fa-ban'></i>&nbsp;".$m[$i]."<br>";
				}
			}
			// Flash message
			Session::flash("system_message", ["level"=>"danger", "type"=>"page", "access"=>"site", "message"=>$message]);
			// Redirect back
			return Redirect::back();
		}
		// Prep new data
		try
		{
		    // Find the user using the user id
		    $user = Sentry::findUserByCredentials([
		    		"email" => $data["current_email"],
		    		"password" => $data["current_password"]
		    	]);

		    // Update the user details
		    $user->email = $data["new_email"];

		    // Update the user
		    if ($user->save())
		    {
		        // User information was updated
				// Flash message
				Session::flash("system_message", ["level"=>"success",  "access"=>"site", "type"=>"page", "message"=>"Your email address was successfully updated."]);
				// Redirect back
				return Redirect::back();
		    }
		    else
		    {
		        // User information was not updated
		        // Flash message
				Session::flash("system_message", ["level"=>"danger",  "access"=>"site", "type"=>"page", "message"=>"Update failed, please try again later."]);
				// Redirect back
				return Redirect::back();
		    }
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    Session::flash("system_message", ["level"=>"danger",  "access"=>"site", "type"=>"page", "message"=>"<i class='fa fa-ban'></i>&nbsp;Current info authentication failed."]);
			// Redirect back
			return Redirect::back();
		}

	}

	public function changePassword()
	{
		// Get user ID
		$id = $this->viewdata["userdata"]->id;
		// Request data
		$data = Input::all();
		// Validation Rules
		$rules = [
			"current_email" => "required",
			"current_password" => "required",
			"new_password" => "required|min:8|confirmed",
			"new_password_confirmation" => "required_with:password"
		];
		// Validation Messages
		$messages = [
			"current_email.required" => "Please enter your current email address.",
			"current_password.required" => "Your password is required.",
			"new_password.required" => "Please enter a password.",
			"new_password.min" => "New password must have atleast 8 characters.",
			"new_password.confirmed" => "Confirmation password does not match new password.",
			"new_password_confirmation.required_with" => "Please confirm password.",
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
				for ($i=0; $i < count($m); $i++) { 
					$message .= "<i class='fa fa-ban'></i>&nbsp;".$m[$i]."<br>";
				}
			}
			// Flash message
			Session::flash("system_message", ["level"=>"danger", "type"=>"page", "access"=>"site", "message"=>$message]);
			// Redirect back
			return Redirect::back();
		}
		
		try
		{
		    // Find the user by the user's ID
		    $user = Sentry::findUserByCredentials([
		    										"password"=>$data["current_password"], 
		    										"email"=>$data["current_email"]
		    									]);

		    // Get the password reset code
		    $resetCode = $user->getResetPasswordCode();

		    // Attempt to reset the user password
	        if ($user->attemptResetPassword($resetCode, $data["new_password"]))
	        {
	        	// Password reset passed
	            // Flash message
				Session::flash("system_message", ["level"=>"success",  "access"=>"site", "type"=>"page", "message"=>"Password was successfully changed."]);
				// Redirect back
				return Redirect::back();
	        }
	        else
	        {
	            // Password reset failed
	            // Flash message
				Session::flash("system_message", ["level"=>"danger",  "access"=>"site", "type"=>"page", "message"=>"Password reset failed, please try again later."]);
				// Redirect back
				return Redirect::back();
	        }
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		   // Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"site", "type"=>"page", "message"=>"<i class='fa fa-ban'></i>&nbsp;Current info authentication failed."]);
			// Redirect back
			return Redirect::back();
		}	
		
	}

}