<?php
use Ao\Data\Models\Headers;
use Ao\Data\Models\Classes;
use Ao\Data\Models\Applications;
use Ao\Data\Models\Afterschool;
class AdmissionController extends SiteController {

	/*
	|--------------------------------------------------------------------------
	| Default Pages Controller
	|--------------------------------------------------------------------------
	|
	| Serve's views for routes without explicit functions
	|
	*/

	/**
	 * Controller construct method
	*/
	public function __construct()
	{
		$this->viewdata["basicdata"] = $this->setBasicData();
		$this->viewdata["sitedata"] = $this->getContentdata("footer");
		$this->viewdata["page"] = "";
		$this->viewdata["pagedata"] = [];
	}

	/**
	 * Serve's a view
	 * @return view
	*/
	public function index()
	{
		$page = $this->getPage("admission");
		$today = date("Y-m-d H:i:s");
		$header = $page->headers()->first();
		$list = Admission::where("close_date", ">=", $today)->with(["aclass", "contactdata1", "contactdata1"])->get();
		// Prep view data
		$this->viewdata["list"] = $list;
		$this->viewdata["header"] = $header;
		$this->viewdata["page"] = "admission";
		$this->viewdata["pagedata"] = $this->getContentdata($page->slug);
		// Serve view
		return View::make('pages.admission.list', $this->viewdata);
	}

	/**
	 * Serve's a view
	 * @return view
	*/
	public function form()
	{
		$header = Headers::where("page", "admission form")->first();
		// Prep view data
		$this->viewdata["header"] = $header;
		$this->viewdata["page"] = "admission";
		// Serve view
		return View::make('pages.admission.form', $this->viewdata);
	}


	/**
	 * Serve's a view
	 * @return view
	*/
	public function afterschool()
	{
		$header = Headers::where("page", "afterschool")->first();
		// Prep view data
		$this->viewdata["header"] = $header;
		$this->viewdata["page"] = "admission";
		// Serve view
		return View::make('pages.admission.afterschool', $this->viewdata);
	}

	/**
	 * Processes a form
	 * @return redirect
	*/
	public function apply()
	{
		// Grab request data
		$data = Input::all();
		// Validation rules
		$rules = [
				"childs_name"=>"required",
				"childs_surname"=>"required",
				"childs_age"=>"required|numeric|min:0",
				"childs_sex"=>"required",
				"childs_birthday"=>"required",
				"address"=>"required",
				"starting_on"=>"required"
		];
		// Validation Messages
		$messages = [
						"childs_name.required" => "This field is required.",
						"childs_surname.required" => "This field is required.",
						"childs_age.required" => "Age must be a number.",
						"childs_age.numeric" => "Age must be a number.",
						"childs_age.min" => "Child's age must 0 or above.",
						"childs_sex.required" => "This field is required.",
						"childs_birthday.required" => "This field is required.",
						"address.required" => "This field is required.",
						"starting_on.required" => "This field is required.",
					];
		// Validation
		$validation = Validator::make($data, $rules, $messages);
		// Check validation state
		if ($validation->fails()) {
			// Redirect back wtih errors
			return Redirect::back()->withInput()->withErrors($validation);
		};
		$data["starting_on"] = date("y-m-d", strtotime($data["starting_on"]));
		$data["childs_birthday"] = date("y-m-d", strtotime($data["childs_birthday"]));
		// Save to database
		$new = Applications::create($data);
		// Create success message
		Session::flash("system_message", 
										["level"=>"success", 
										 "type"=>"application", 
										 "access"=>"site", 
										 "message"=>"<strong>Congratulations!</strong> Your application was successfull, we will get back to you shortly!"
										]);
		// Redirect to previous page
		return Redirect::back();

	}

	/**
	 * Processes a form
	 * @return redirect
	*/
	public function afterschool_apply()
	{
		// Grab request data
		$data = Input::all();
		// Validation rules
		$rules = [
				"childs_name"=>"required",
				"childs_surname"=>"required",
				"childs_sex"=>"required",
				"dob"=>"required",
				"address"=>"required",
				"starting_on"=>"required",
				"parents_name"=>"required",
				"parents_phone"=>"required",
				"parents_email"=>"required|email",
				"parents_occupation"=>"required"
		];
		// Validation Messages
		$messages = [
						"childs_name.required" => "This field is required.",
						"childs_surname.required" => "This field is required.",
						"childs_sex.required" => "This field is required.",
						"dob.required" => "This field is required.",
						"address.required" => "This field is required.",
						"starting_on.required" => "This field is required.",
						"parents_name.required"=>"This field is required.",
						"parents_phone.required"=>"This field is required.",
						"parents_email.required"=>"This field is required.",
						"parents_email.email"=>"Please enter a valid email.",
						"parents_occupation.required"=>"This field is required."
					];
		// Validation
		$validation = Validator::make($data, $rules, $messages);
		// Check validation state
		if ($validation->fails()) {
			// Redirect back wtih errors
			return Redirect::back()->withInput()->withErrors($validation);
		};
		$data["starting_on"] = date("y-m-d", strtotime($data["starting_on"]));
		$data["dob"] = date("y-m-d", strtotime($data["dob"]));
		$clubs = [];
		$clubs["Pythagora’s Corner"] = Input::get("pythagoras_corner", 0);
		$clubs["Gardening"] = Input::get("gardening", 0);
		$clubs["ICT Whiz"] = Input::get("ict_whiz", 0);
		$clubs["Lady Class"] = Input::get("lady_class", 0);
		$clubs["Dance"] = Input::get("dance", 0);
		$clubs["Taekwondo"] = Input::get("taekwondo", 0);
		$clubs["Book Club"] = Input::get("book_club", 0);
		$clubs["Science Club"] = Input::get("science_club", 0);
		$clubs["Music"] = Input::get("music", 0);
		$clubs["Drama/Public Speaking"] = Input::get("drama_public_speaking", 0);
		$clubs["Cheerleading"] = Input::get("cheerleading", 0);
		$data["clubs"] = serialize($clubs);
		// Save to database
		$new = Afterschool::create($data);
		// Create success message
		Session::flash("system_message", 
										["level"=>"success", 
										 "type"=>"afterschool", 
										 "access"=>"site", 
										 "message"=>"<strong>Congratulations!</strong> Your application was successfull, we will get back to you shortly!"
										]);
		// Redirect to previous page
		return Redirect::back();

	}

}
