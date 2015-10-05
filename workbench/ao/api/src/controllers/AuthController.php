<?php namespace Ao\Api\Controllers;

use Ao\Data\Models\Basicdata;
class AuthController extends ApiController{

	public function forgot()
	{
		// Grab request data
		$data = \Input::all();
		// Validation rules
		$rules = [	
					"email"=>"required|email"
				];
		// Validation messages
		$messages = [
					"email.required"=>"The email field is required.",
					"email.email"=>"Please enter a valid email."
					];
		// Prep return data
		$returnData = \Config::get("settings.response_data");
		// Validate
		$validation = \Validator::make($data, $rules, $messages);
		// Check validations
		if ($validation->fails()) {
			// Prep error message
			$responsmMessage = "";
			$errorMessages = $validation->errors()->getMessages();
			foreach($errorMessages as $errorMessage){
				for ($i=0; $i < $count($errorMessage); $i++) { 
					$responsmMessage .= $errorMessage[$i]."<br>";
				}
			}
			$returnData["message"] = $responsmMessage;
			// Check if request is ajax
			if (\Request::ajax()) {
				// Set respons code
				$returnData["code"] = 200;
				return \Response::json($returnData, 200);
			}
			// Else if request isn't ajax
			$returnData["type"] = "loginForm";
			\Session::flash("system_message", $returnData);
			return \Redirect::back();
		}
		// Else if validation passes
		try
		{
		    // Find the user by the email address
		    $user = \Sentry::findUserByLogin($data["email"]);
		    // Get the password reset code
		    $resetCode = $user->getResetPasswordCode();
		    // Get basic data
		    $basicdata = Basicdata::first();
		    // Prep email data
		    $emailData = [
		    			"to"=>["email" => $user->email, "name"=>$user->first_name." ".$user->last_name],
		    			"from"=>\Config::get("mail.from"),
		    			"subject"=>"Reset Password",
		    			"basicdata"=>$basicdata,
		    			"body"=>"Please click on this link to reset your password! 
		    						<a href=\"".\URL::to('auth/reset')."/".$resetCode."\">"
		    						.\URL::to('auth/reset/')."/".$resetCode."</a>",
		    		];
		    // Send mail
			\Mail::later(10, '_emails.basic', $emailData, function($message) use($emailData){
				$message->from($emailData["from"]["address"], $emailData["from"]["name"])
						->to($emailData["to"]["email"], $emailData["to"]["name"])
						->subject($emailData["subject"]);
			});
		    // Update success message
			$returnData["message"] = "A reset code has been sent to your email please click on the link in the mail to complete this action!";
			$returnData["level"] = "success";
			$returnData["status"] = "success";
			$returnData["type"] = "forgotForm";
		    // Check if request is ajax
			if (\Request::ajax()) {
				// Set respons code
				$returnData["code"] = 200;
				return \Response::json($returnData, 200);
			}
			// Update return data
		    \Session::flash("system_message", $returnData);
		    // Go to return
		    return \Redirect::to("auth/login");
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    $returnData["message"] = "Email address does not exist!";
			$returnData["type"] = "forgotForm";
			// Check if request is ajax
			if (\Request::ajax()) {
				// Set respons code
				$returnData["code"] = 200;
				return \Response::json($returnData, 200);
			}
		    \Session::flash("system_message", $returnData);
			// Return a response			
			return \Redirect::back();
		}
	}

	public function reset($resetCode)
	{
		$data = \Input::all();
		// Validation rules
		$rules = [	
					"password"=>"required|min:8",
					"password_confirmation"=>"required|same:password",
				];
		// Validation messages
		$messages = [
					"password.required"=>"The email field is required.",
					"password.min"=>"Password must contain atleast eight(8) characters.",
					"password_confirmation.required"=>"The Password confirmation field is required.",
					"password_confirmation.sam"=>"The Password confirmation must be equalt to new password.",
					];
		// Prep return data
		$returnData = \Config::get("settings.response_data");
		$returnData["type"] = "page";
		// Validate
		$validation = \Validator::make($data, $rules, $messages);
		// Check validations
		if ($validation->fails()) {
			// Prep error message
			$responsmMessage = "";
			$errorMessages = $validation->errors()->getMessages();
			foreach($errorMessages as $errorMessage){
				$responsmMessage .= $errorMessage[0]."<br>";
			}
			$returnData["message"] = $responsmMessage;
			$returnData["type"] = "loginForm";
			// Check if request is ajax
			if (\Request::ajax()) {
				// Set respons code
				$returnData["code"] = 200;
				return \Response::json($returnData, 200);
			}
			// Else if request isn't ajax
			\Session::flash("system_message", $returnData);
			return \Redirect::back();
		}
		// Prep return data
		$returnData["type"] = "loginForm";
		try{
			// Find the user
			$user = \Sentry::findUserByResetPasswordCode($resetCode);
			// Attempt to reset password
			if ($user->attemptResetPassword($resetCode, $data["password"])) {
				// Password reset passed
				$emailData = [
	    			"to"=>["email" => $user->email, "name"=>$user->first_name." ".$user->last_name],
	    			"from"=>\Config::get("settings.anonymous_email"),
	    			"subject"=>"Reset Password (Complete)",
	    			"mailMessage"=>"Your password reset was successful click  
	    						<a href=\"".\URL::to('auth/login')."\">here</a> to login now",
	    		];
			    // Fire email event
			    \Event::fire('basic.email', [$emailData]);
				// Update return message
				$returnData["message"] = "Password successfully rest, click <a href=\"".\URL::to('auth/login')."\" class=\"gray-link\">here</a> to login now!";
				$returnData["status"] = "success";
				$returnData["level"] = "success";
				// Check if request is ajax
				if (\Request::ajax()) {
					// Set respons code
					$returnData["code"] = 200;
					return \Response::json($returnData, 200);
				}
				// Else if request isn't ajax
				\Session::flash("system_message", $returnData);
				return \Redirect::to("auth/login");
			}
			else{
				// Password reset failed
				$returnData["message"] = "Password reset failed!";
				// Check if request is ajax
				if (\Request::ajax()) {
					// Set respons code
					$returnData["code"] = 200;
					return \Response::json($returnData, 200);
				}
				// Else if request isn't ajax
				\Session::flash("system_message", $returnData);
				return \Redirect::to("auth/login");
			}
		}
		catch(\Cartalyst\Sentry\Users\UserNotFoundException $e){
			// Update return message
			$returnData["message"] = "Invalid password reset code!";
			// Check if request is ajax
			if (\Request::ajax()) {
				// Set respons code
				$returnData["code"] = 200;
				return \Response::json($returnData, 200);
			}
			// Else if request isn't ajax
			\Session::flash("system_message", $returnData);
			return \Redirect::to("login");
		}
	}

	public function login()
	{
		// Grab request data
		$data = \Input::all();
		// Validation rules
		$rules = [	
					"email"=>"required|email",
					"password"=>"required",
				];
		// Validation messages
		$messages = [
					"email.required"=>"The email field is required.",
					"email.email"=>"Please enter a valid email.",
					"password.required"=>"The Password field is required.",
					];
		// Prep return data
		$returnData = \Config::get("settings.response_data");
		// Validate
		$validation = \Validator::make($data, $rules, $messages);
		// Check validations
		if ($validation->fails()) {
			// Prep error message
			$responsmMessage = "";
			$errorMessages = $validation->errors()->getMessages();
			foreach($errorMessages as $errorMessage){
				for ($i=0; $i < $count($errorMessage); $i++) { 
					$responsmMessage .= $errorMessage[$i]."<br>";
				}
			}
			$returnData["message"] = $responsmMessage;
			// Check if request is ajax
			if (\Request::ajax()) {
				// Set respons code
				$returnData["code"] = 200;
				return \Response::json($returnData, 200);
			}
			// Else if request isn't ajax
			$returnData["type"] = "loginForm";
			\Session::flash("system_message", $returnData);
			return \Redirect::back();
		}
		// Else if validation passes
		try
		{
			$credentials = ["email"=> $data["email"], "password"=>$data["password"]];
		    // Remember user
		    $remember = \Input::get("remember", false);
		    // Authenticate the user
		    $user = \Sentry::authenticate($credentials, $remember);
		    // Check if request is ajax
			if (\Request::ajax()) {
				// Set respons code
				$returnData["code"] = 200;
				$returnData["level"] = "success";
				$returnData["status"] = "success";
				$returnData["message"] = "<i class=\"fa fa-check\"></i> Login successful!";
				return \Response::json($returnData, 200);
			}
		    // Go to return
		    $return = urldecode(\Input::get('return', URL::to('myaccount')));
		    return \Redirect::to($return);
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    $returnData["message"] = "Wrong email or password!";
			// Check if request is ajax
			if (\Request::ajax()) {
				// Set respons code
				$returnData["code"] = 200;
				return \Response::json($returnData, 200);
			}
			$returnData["type"] = "loginForm";
		    \Session::flash("system_message", $returnData);
			// Return a response			
			return \Redirect::back();
		}
		catch (\Cartalyst\Sentry\Users\UserNotActivatedException $e)
		{
			$returnData["message"] = "Account is not activated!";
		   	// Check if request is ajax
			if (\Request::ajax()) {
				// Set respons code
				$returnData["code"] = 200;
				return \Response::json($returnData, 200);
			}
			$returnData["type"] = "loginForm";
		    \Session::flash("system_message", $returnData);
			// Return a response			
			return \Redirect::back();
		}
		catch (\Cartalyst\Sentry\Throttling\UserSuspendedException $e)
		{
		    $returnData["message"] = "Account is suspended!";
		   	// Check if request is ajax
			if (\Request::ajax()) {
				// Set respons code
				$returnData["code"] = 200;
				return \Response::json($returnData, 200);
			}
			$returnData["type"] = "loginForm";
		    \Session::flash("system_message", $returnData);
			// Return a response			
			return \Redirect::back();
		}
		catch (\Cartalyst\Sentry\Throttling\UserBannedException $e)
		{
			$returnData["message"] = "Account is banned!";
		   	// Check if request is ajax
			if (\Request::ajax()) {
				// Set respons code
				$returnData["code"] = 200;
				return \Response::json($returnData, 200);
			}
			$returnData["type"] = "loginForm";
		    \Session::flash("system_message", $returnData);
			// Return a response			
			return \Redirect::back();
		}
	}

	public function logout()
	{
		// Prep return data
		$returnData = \Config::get("settings.response_data");
	    // Authenticate the user
	    \Sentry::logout();
		$returnData["message"] = "You have been logged out!";
		$returnData["type"] = "loginForm";
		$returnData["status"] = "success";
		$returnData["level"] = "success";
		// Flash system message
		\Session::flash("system_message", $returnData);
	    // Go to login page
	    return \Redirect::to("login");
	}

}