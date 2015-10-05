<?php
use Ao\Data\Models\Files;
use Ao\Data\Models\Basicdata;
class RequestController extends SiteController {

	public $basicdata;

	public function __construct()
	{
		$this->basicdata = Basicdata::first();
	}

	/**
	 * Sends a message
	 * @return redirect
	*/
	public function sendMessage()
	{
		// Get request data
		$data = Input::all();
		// Validation rules
		$rules = [
					"name"=>"required",
					"email"=>"required|email",
					"comment"=>"required"
				];
		// Validation messages
		$messages = [
						"name.required"=>"This field is required.",
						"email.required"=>"This is field is required.",
						"email.email"=>"Please enter a valid email address.",
						"comment.required"=>"This field is required."
					];
		// Validation
		$validation = Validator::make($data, $rules, $messages);
		// Check if validation failed
		if ($validation->fails()) {
			// Return to previous page
			return Redirect::back()->withInput()->withErrors($validation);
		}
		 $data["subject"] = "Website Contact Form Message";
		// Prep mail data
		$sendData = [
			"to" => "info@testingdomain.com",
			"subject" => $data["subject"],
			"from" => $data["email"],
			"name" => $data["name"],
			"body" => $data["comment"]
		];
		$notifyData = [
			"to" => $data["email"],
			"subject" => "Auto Reply Notification",
			"from" => "noreply@testingdomain.com",
			"name" => $data["name"],
			"body" => "This is an auto reply notification to let you know that we've recieved your message and will reply yu shortly."
		];
		// Send mail
		Mail::later(10, '_emails.basic', $sendData, function($message) use($sendData){
			$message->from($sendData["from"], $sendData["name"])
					->to($sendData["to"], "Acorns And Oaks")
					->subject($sendData["subject"]);
		});
		// Send Notification
		Mail::later(10, '_emails.basic', $notifyData, function($message) use($notifyData){
			$message->from($notifyData["from"], "Acorns And Oaks")
					->to($notifyData["to"], $notifyData["name"])
					->subject($notifyData["subject"]);
		});
		// Flash notification mail
		Session::flash("system_message", ["level"=>"success", "type"=>"contact_form", "access"=>"site", "message"=>"<i class='fa fa-check'></i>&nbsp;Your message was successfully sent!"]);
		// Return to prvious page
		return Redirect::back();
	}

	/**
	 * Sends an application email message
	 * @return redirect
	*/
	public function apply()
	{
		// Get request data
		$data = Input::all();
		// Validation rules
		$rules = [
					"first_name"=>"required",
					"last_name"=>"required",
					"phone"=>"required|numeric",
					"email"=>"required|email",
					"program"=>"required"
				];
		// Validation messages
		$messages = [
						"first_name.required"=>"The first name field is required.",
						"last_name.required"=>"The last name field is required.",
						"email.required"=>"The email field is required.",
						"email.email"=>"Please enter a valid email address.",
						"phone.required"=>"The phone field is required.",
						"phone.numeric"=>"Please only numbers for your phone number.",
						"program.required"=>"Please select the program you want to apply for."
					];
		// Validation
		$validation = Validator::make($data, $rules, $messages);
		// Check if validation failed
		if ($validation->fails()) {
			$message = "";
			$errors = $validation->errors()->getMessages();
			foreach($errors as $error){
				$message .= "<span class='m-r-10'><i class='fa fa-times'></i>&nbsp;".$error[0]."</span>";
			}
			// Flash notification
			Session::flash("system_message", ["level"=>"danger", "type"=>"page", "access"=>"site", "message"=>$message]);
			// Return to previous page
			return Redirect::back()->withInput()->withErrors($validation);
		}
		$data["subject"] = $this->basicdata->shortname." Program Application Request";
		$applicationMessage = '<p>New Application For <strong> '.$data['program'].'</strong></p>'.
							  '<p><strong>First Name:</strong> '.$data['first_name'].'</p>'.
							  '<p><strong>Last Name:</strong> '.$data['last_name'].'</p>'.
							  '<p><strong>Email:</strong> '.$data['email'].'</p>'.
							  '<p><strong>Phone:</strong> '.$data['phone'].'</p>';
		// Check for comment and add
		$applicationMessage .= ( strlen($data['comment']) > 0 ) ? '<p><strong>Comment:</strong>'.$data['comment'].'</p>' : '';
		// Prep mail data
		$sendData = [
			"to" => Config::get("mail.from.address"),
			"toName" => Config::get("mail.from.name"),
			"subject" => $data["subject"],
			"from" => $data["email"],
			"name" => $data["first_name"]." ".$data["last_name"],
			"body" => $applicationMessage,
			"basicdata" => $this->basicdata,
		];
		$notifyData = [
			"to" => $data["email"],
			"subject" => "Auto Reply Notification",
			"from" => Config::get("mail.from.address"),
			"name" => $data["first_name"]." ".$data["last_name"],
			"body" => "We've recieved your application and will reply you shortly.",
			"basicdata" => $this->basicdata,
		];
		// Send mail
		Mail::later(10, '_emails.basic', $sendData, function($message) use($sendData){
			$message->from($sendData["from"], $sendData["name"])
					->to($sendData["to"], $sendData["toName"])
					->subject($sendData["subject"]);
		});
		// Send Notification
		Mail::later(10, '_emails.basic', $notifyData, function($message) use($notifyData){
			$message->from($notifyData["from"], Config::get("mail.from.name"))
					->to($notifyData["to"], $notifyData["name"])
					->subject($notifyData["subject"]);
		});
		// Flash notification mail
		Session::flash("system_message", ["level"=>"success", "type"=>"page", "access"=>"site", "message"=>"<i class='fa fa-check'></i>&nbsp;Your application was successfully sent!"]);
		// Return to prvious page
		return Redirect::back();
	}

	public function myaccountAddress()
	{
		# code...
	}

	public function downloadFile($key)
	{
		// Find requested model
		$file = Files::where("downloadkey", $key)->first();
		// Validate model
		if (!$file) {
			return App::abort(404, "File not found!");
		}
		// Check if file exists
		if (!File::exists($file->url)) {
			return App::abort(404, "File not found!");
		}
		// Check if file is not downloadable
		if (is_null($file->downloadable)) {
			return App::abort(404, "File not found!");
		}
		// Get file original name
		$file->info = unserialize($file->info);
		// Download file
		return Response::download($file->url, $file->info["original_name"], ["Content-Type"=>""]);
	}
}
