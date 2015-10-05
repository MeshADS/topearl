<?php
use Ao\Data\Models\User;
class ContactlistController extends SiteController {

	public function __construct(User $model)
	{
		Parent::__construct();

		$this->model = $model;
	}

	public function index()
	{
		// Grab request data
		$sort = Input::get('sort', 'created_at');
		// Build page data
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
		// Get page data
		$list = $this->viewdata["userdata"]->phonenumbers();
		switch ($sort) {
			case 'a-z':
					$list = $list->orderBy("name", "asc");
				break;
			case 'z-a':
					$list = $list->orderBy("name", "desc");
				break;
			default:
					$list = $list->orderBy("created_at", "desc");
				break;
		}
		$list = $list->paginate(20);
		// Prep view data
		$this->viewdata["current_menu"] = 7;
		$this->viewdata["list"] = $list;
		$this->viewdata["header"] = $header;
		$this->viewdata["pageslug"] = $page->slug;
		$this->viewdata["page"] = $page;
		$this->viewdata["pagedata"] = $this->getContentdata($page->slug);
		// Serve view
		return View::make('pages.myaccount.contact-list', $this->viewdata);	
	}

	/*
	* Save new resource
	*/
	public function save()
	{
		$id = $this->viewdata["userdata"]->id;
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
			Session::flash("system_message", ["level"=>"danger",  "access"=>"site", "type"=>"page", "message"=>$message]);
			// Redirect back
			return Redirect::back()->withInput();
		}
		// Find requested model
		$item = $this->model->find($id);
		// Validate user program relationship
		$exists = $item->phonenumbers()->where("number", $data["number"])->first();
		if ($exists) {
			// Flash message
			Session::flash("system_message", ["level"=>"info",  "access"=>"site", "type"=>"page", "message"=>"That contact data already exists."]);
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
		Session::flash("system_message", ["level"=>"success",  "access"=>"site", "type"=>"page", "message"=>"New contact data successfully created."]);
		// Redirect back
		return Redirect::back();
	}

	/*
	* Update existing resource
	*/
	public function update($phone)
	{
		$id = $this->viewdata["userdata"]->id;
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
			Session::flash("system_message", ["level"=>"danger",  "access"=>"site", "type"=>"page", "message"=>$message]);
			// Redirect back
			return Redirect::back();
		}
		// Find requested model
		$item = $this->model->find($id);
		// Validate user program relationship
		$phone = $item->phonenumbers()->find($phone);
		if (!$phone) {
			// Flash message
			Session::flash("system_message", ["level"=>"info",  "access"=>"site", "type"=>"page", "message"=>"Contact data not found."]);
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
		Session::flash("system_message", ["level"=>"success",  "access"=>"site", "type"=>"page", "message"=>"Contact data successfully updated."]);
		// Redirect back
		return Redirect::back();
	}

	public function destroy($phone)
	{
		$id = $this->viewdata["userdata"]->id;
		// Find user model
		$item = $this->model->with("phone")->find($id);
		if (!$item) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"site", "type"=>"page", "message"=>"User not found."]);
			// Redirect back
			return Redirect::back();
		}
		$phone = $item->phonenumbers()->find($phone);
		if (!$phone) {
			// Flash message
			Session::flash("system_message", ["level"=>"danger",  "access"=>"site", "type"=>"page", "message"=>"Contact data not found."]);
			// Redirect back
			return Redirect::back();
		}
		// Update primary
		if ($item->phone->id == $phone->id) {
			$newPhone = $item->phonenumbers()->where("id", "!=", $phone->id)->first();
			if (!$newPhone) {
				// Flash message
				Session::flash("system_message", ["level"=>"danger",  "access"=>"site", "type"=>"page", "message"=>"Operation canceled, couldn't find an alternative contact data."]);
				// Redirect back
				return Redirect::back();
			}
			// Set new phone number
			$item->update(["phone_id" => $newPhone->id]);
		}
		// Delete
		$phone->delete();
		// Flash message
		Session::flash("system_message", ["level"=>"warning",  "access"=>"site", "type"=>"page", "message"=>"Contact data deleted."]);
		// Redirect back
		return Redirect::back();
	}

}