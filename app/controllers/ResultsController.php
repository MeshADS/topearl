<?php
class ResultsController extends SiteController {

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
		// Load data
		$sort = Input::get("sort", "newest");
		$list = $this->viewdata["userdata"]->results()
					->with(["semester", "program", "resultslist"=>function($query){
						$query->orderBy("position", "asc");
					}]);
			switch ($sort) {
				case 'oldest':
						$list = $list->orderBy("created_at", "asc");
					break;				
				default:
						$list = $list->orderBy("created_at", "desc");
					break;
			}
		$list = $list->paginate(20);
		// Prep view data
		$this->viewdata["current_menu"] = 5;
		$this->viewdata["list"] = $list;
		$this->viewdata["header"] = $header;
		$this->viewdata["pageslug"] = $page->slug;
		$this->viewdata["page"] = $page;
		$this->viewdata["pagedata"] = $this->getContentdata($page->slug);
		// Serve view
		return View::make('pages.myaccount.results', $this->viewdata);	
	}

}