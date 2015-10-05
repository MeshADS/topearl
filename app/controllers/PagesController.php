<?php
use Ao\Data\Models\Headers;
use Ao\Data\Models\Images;
class PagesController extends SiteController {

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
		Parent::__construct();
	}

	/**
	 * Serve's the home page
	 * @return view
	*/
	public function home()
	{
		$page = $this->getPage("home", "page");
		$group = $this->getPage("home", "group");
		
		$backgrounds = $this->getPage("backgrounds", "group");
		$date = date("Y-m-d H:i:s");
		$nextEvent = null;
		$sliders = $page->headers()->orderBy("order", "asc")->get();
		$events = Schoolcalendar::orderBy("schedule_starts", "asc")
								->where("schedule_starts" ,  ">", $date)
								->with(["category" => function($query){
									$query->where("type", "calendar");
								}])->get();
		// Get next event
		if (count($events) > 0) {
			$nextEvent = $events[0];
		}
		// Get latest posts
		$latestposts = Posts::orderBy("created_at", "desc")
						->with(["category" => function($query){
							$query->where("type", "posts");
						}])->take(6)->get();
		// Get staff data
		$staff = Staff::orderBy("pos", "asc")->take(10)->get();
		// Get page images
		$images = ($group) ? $group->images()->get() : [];
		$imagesArr = [];
		foreach($images as $image){
			$imagesArr[$image->title] = $image;
		};
		$this->viewdata["pageImages"] = $imagesArr;
		// Prep view data
		$this->viewdata["staff"] = $staff;
		$this->viewdata["latestposts"] = $latestposts;
		$this->viewdata["events"] = $events;
		$this->viewdata["nextEvent"] = $nextEvent;
		$this->viewdata["sliders"] = $sliders;
		$this->viewdata["page"] = $page;
		$this->viewdata["pagedata"] = $this->getContentdata($page->slug);
		// Serve view
		return View::make('pages.other.home', $this->viewdata);
	}

	/**
	 * Serve's the contact page
	 * @return view
	*/
	public function contact_us()
	{
		$page = $this->getPage("contact-us", "page");
		$header = $page->headers()->orderBy("order", "asc")->first();
		// Prep view data
		$this->viewdata["header"] = $header;
		$this->viewdata["pageslug"] = $page->slug;
		$this->viewdata["page"] = $page;
		$this->viewdata["pagedata"] = $this->getContentdata($page->slug);
		// Serve view
		return View::make('pages.other.contact_us', $this->viewdata);
	}

	/**
	 * Serve's the programs page
	 * @return view
	*/
	public function programs()
	{
		$page = $this->getPage("programs", "page");
		$group = $this->getPage("programs", "group");
		$sections = $group->images()->orderBy("order", "asc")->get();
		$header = $page->headers()->orderBy("order", "asc")->first();
		// Prep sections data
		$sectionsArr = [];
		foreach ($sections as $section) {
			$section->background = $section->image;
			$section->group = $this->getPage(Slugify::slugify($section->title), "group");
			$section->list_group = $this->getPage(Slugify::slugify($section->title." List"), "group");
			$section->image = ($section->group) ? $section->group->images()->where("title", "Image")->first() : false;
			$section->list = ($section->list_group) ? $section->list_group->images()->orderBy("order", "asc")->get() : [];
			// Update section array
			$sectionsArr[] = $section;
		}
		// Prep view data
		$this->viewdata["header"] = $header;
		$this->viewdata["sections"] = $sectionsArr;
		$this->viewdata["pageslug"] = $page->slug;
		$this->viewdata["page"] = $page;
		$this->viewdata["pagedata"] = $this->getContentdata($page->slug);
		// Serve view
		return View::make('pages.other.programs', $this->viewdata);
	}

}
