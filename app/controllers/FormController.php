<?php
use Ao\Data\Models\Forms;
use Ao\Data\Models\Headers;
class FormController extends SiteController {

	public function __construct(Forms $model)
		{
			Parent::__construct();
		}

	/**
	 * Display the specified resource.
	 *
	 * @param  string  $form
	 * @return Response
	 */
	public function show($form)
	{
		// Get model
		$form = $this->model->where("slug", $form)->where("publish", 1)->first();
		// Validate model
		if (!$form) {
			return App::abort(404);
		}
		// Set default header title
		if (isset($this->viewdata["header"]->title)) {
			$this->viewdata["header"]->title = $form->name;
		}
		// Get elements
		$elements = $form->elements()->orderBy("position", "asc")->with("listValues")->get()->groupBy("groupie");
		$elementsArr = [];
		// Convert elements rules
		foreach ($elements as $group => $element) {
			foreach($element as $el){
				$el->rules = unserialize($el->rules);
				$elementsArr[$group][] = $el;
			}
		}
		// Assign elements to form
		$form->elements = $elementsArr;
		// Get page data
		$page = $this->getPage($form->name);
		// Validate page data
		if ($page) {
			// Prep page data
			$this->viewdata["header"] = $page->headers()->first();
		}
		// Prep view data
		$this->viewdata["form"] = $form;
		$this->viewdata["elements"] = $elements;
		// Load view
		return View::make("pages.other.form", $this->viewdata);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($form)
	{
		// Grab request data
		$data = Input::except("_token");
		// Find model
		$form = $this->model->where("slug", $form)->where("publish", 1)->first();
		// Validate model
		if (!$form) {
			// Set system message
			Session::flash("system_message", 
										["level"=>"danger", 
										 "type"=>"page", 
										 "access"=>"site", 
										 "message"=>"<strong>Error!</strong> Form not found!"
										]);
			// redirect to previous page
			return Redirect::back();
		}
		// Prep data
		$data = serialize($data);
		// Save data
		$newSubmitions = $form->submitions()->create(["data"=>$data]);
		// Send notification by mail
		if ($form->notify === 1) {
			Event::fire("form.submitted", array($form));
		};
		// Set system message
		Session::flash("system_message", 
									["level"=>"success", 
									 "type"=>"page", 
									 "access"=>"site", 
									 "message"=>'<strong><i class="fa fa-thumbs-up"></i>&nbsp;Success!</strong> Form submitted!'
									]);
		// redirect to previous page
		return Redirect::back();
	}



}
