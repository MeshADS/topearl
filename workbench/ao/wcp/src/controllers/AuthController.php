<?php namespace Ao\Wcp\Controllers;

use \Sentry;
use \Input;
use \Redirect;
use \Session;
use \Validator;
use \View;
use Ao\Data\Models\Basicdata;

class AuthController extends WcpController{

	public function __construct()
	{
		$this->beforeFilter("auth.wcp.in");
		// Set default view message
		$this->viewdata["view_message"] = null;
		// Check for flashed site messages
		if (Session::has('system_message')) {
			$system_message = Session::get('system_message');
			if ($system_message["access"] == "wcp") {
				$this->viewdata["view_message"] = $system_message;
			}
		}
		// Load basic data
		$basic_data = Basicdata::orderBy("created_at", "desc")->first();
		$this->viewdata["basic_data"] = $basic_data;
	}


	public function showLogin()
	{
		return View::make('wcp::pages.auth.login', $this->viewdata);
	}

	public function login()
	{
		// All data in the request
		$data = Input::all();
		// Validations rules
		$rules = [
					"email" => "required|email",
					"password" => "required"
		];
		// Validation messages
		$messages = [
					"email.required" => "The email address is required.",
					"email.email" => "Please enter a valid email address.",
					"password.required" => "The password ia required."
		];
		// Validations
		$validation = Validator::make($data, $rules, $messages);
		// Validation condition
		if ($validation->fails()) {
			// Save error messages in error bag
			$messages = $validation->errors()->getMessages();
			$message = "";
			foreach ($messages as $m) {
				$message .= $m[0]."<br>";
			}
			// Wrong email or password
			Session::flash("system_message",["level"=>"danger", "access"=>"wcp", "type" => "login", "message" => $message]);
			// Return a response			
			return Redirect::back();
		}
		// Login user
		try
		{
		    // Login credentials
		    $credentials = array(
		        'email'    => Input::get("email"),
		        'password' => Input::get("password")
		    );

		    // Remember user
		    $remember = Input::get("remember", false);

		    // Authenticate the user
		    $user = Sentry::authenticate($credentials, $remember);

		    // Go to return
		    $return = urldecode(Input::get('return','/'));
		    return Redirect::to($return);
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    // User was not found
		    Session::flash("system_message",["level"=>"danger", "access"=>"wcp", "type" => "login", "message" => "Wrong email or password."]);
			// Return a response			
			return Redirect::back();
		}
		catch (\Cartalyst\Sentry\Users\UserNotActivatedException $e)
		{
		    // User is not activated
		    Session::flash("system_message",["level"=>"danger", "access"=>"wcp", "type" => "login", "message" => "Account is not activated."]);
			// Return a response			
			return Redirect::back();
		}
		catch (\Cartalyst\Sentry\Throttling\UserSuspendedException $e)
		{
		    // User is suspended
		    Session::flash("system_message",["level"=>"danger", "access"=>"wcp", "type" => "login", "message" => "Account is suspended."]);
			// Return a response			
			return Redirect::back();
		}
		catch (\Cartalyst\Sentry\Throttling\UserBannedException $e)
		{
			// User is banned
		    Session::flash("system_message",["level"=>"danger", "access"=>"wcp", "type" => "login", "message" => "Account is banned!"]);
			// Return a response			
			return Redirect::back();
		}
	}


} 

