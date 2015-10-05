<?php
use Ao\Data\Models\Files;
use Ao\Data\Models\Basicdata;
class MessagesController extends SiteController {

	public function __construct()
	{
		Parent::__construct();
	}

	public function index()
	{
		// Grab request data
		$sort = Input::get('sort', 'newest');
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
		$list = $this->viewdata["userdata"]->messages()->withPivot("state")->with("sender");
		switch ($sort) {
			case 'oldest':
					$list = $list->orderBy("created_at", "asc");
				break;
			case 'unread':
					$list = $list->orderBy("state", "asc")->orderBy("created_at", "desc");
				break;
			default:
					$list = $list->orderBy("created_at", "desc");
				break;
		}
		$list = $list->paginate(20);
		// Prep view data
		$this->viewdata["current_menu"] = 3;
		$this->viewdata["list"] = $list;
		$this->viewdata["header"] = $header;
		$this->viewdata["pageslug"] = $page->slug;
		$this->viewdata["page"] = $page;
		$this->viewdata["pagedata"] = $this->getContentdata($page->slug);
		// Serve view
		return View::make('pages.myaccount.messages', $this->viewdata);	
	}

	public function show($id)
	{
		// Get message model
		$item = $this->viewdata["userdata"]->messages()->withPivot("state")->with("sender")->find($id);
		// Validate model
		if (!$item) {
			return App::abort(404, "Message not found!");
		}
		// Set user
		$user = $this->viewdata["userdata"];
		// Mark as read
		if ($item->state < 1) {
			$user->messages()
			->updateExistingPivot($id, ["state" => 1], false);
		}
		// Grab request data
		$sort = Input::get('sort', 'newest');
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
		// Prep view data
		$this->viewdata["current_menu"] = 3;
		$this->viewdata["item"] = $item;
		$this->viewdata["header"] = $header;
		$this->viewdata["pageslug"] = $page->slug;
		$this->viewdata["page"] = $page;
		$this->viewdata["pagedata"] = $this->getContentdata($page->slug);
		// Serve view
		return View::make('pages.myaccount.showMessage', $this->viewdata);	
	}

	public function mark($action)
	{
		// Validate action
		if ($action != "read" && $action != "unread") {
			// Flash system message
			Session::flash("system_message", ["level"=>"danger", "type"=>"page", "access"=>"site", "message"=>"<i class='fa fa-ban'></i>&nbsp;Invalid action selected!"]);
			// Return to prvious page
			return Redirect::back();
		}
		// Determine action type 
		switch ($action) {
			case 'read':
					$actionType = 1;
				break;
			
			default:
					$actionType = 0;
				break;
		}
		// Grab request data
		$data = Input::all();
		// Validation rules
		$rules = [];
		// Validation messages
		$messages = [];
		// Validation
		$validation = Validator::make($data, $rules, $messages);
		// Check validation
		if ($validation->fails()) {
			$message = "";
			// Get error messages
			$errors = $validation->errors()->getMessages();
			// Loop through errors
			foreach ($errors as $error) {
				for ($i=0; $i < count($error); $i++) { 
					$message .= "<i class='fa fa-ban'></i>&nbsp;".$error[$i]."<br>";
				}
			}
			// Flash system message
			Session::flash("system_message", ["level"=>"danger", "type"=>"page", "access"=>"site", "message"=>$message]);
			// Return to prvious page
			return Redirect::back();
		}
		// Grab user data
		$user = $this->viewdata["userdata"];
		// Update messages
		foreach($data["list"] as $id){
			$user->messages()->updateExistingPivot($id, ["state" => $actionType], false);
		}
		// Flash system message
		Session::flash("system_message", ["level"=>"success", "type"=>"page", "access"=>"site", "message"=>"<i class='fa fa-check'></i>&nbsp;Selected items have been set to ".$action."!"]);
		// Return to prvious page
		return Redirect::back();
	}

}