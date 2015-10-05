<?php
use Ao\Data\Models\Headers;
use Ao\Data\Models\Images;
use Acme\Calendar;
class AboutController extends SiteController {

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
	 * Serve's about us page
	 * @return view
	*/
	public function about_us()
	{
		$page = $this->getPage("about-us", "page");
		$this->opages["students"] = $this->getPage("students", "group");
		$this->opages["backgrounds"] = $this->getPage("backgrounds", "group");
		$date = date("Y-m-d H:i:s");
		$nextEvent = null;
		$header = $page->headers()->first();
		$events = Schoolcalendar::orderBy("schedule_starts", "asc")
								->where("schedule_starts" ,  ">", $date)
								->with(["category" => function($query){
									$query->where("type", "calendar");
								}])->get();
		// Get staff data
		$staff = Staff::orderBy("pos", "asc")->take(10)->get();
		$students = $this->opages["students"]->images()->orderBy("order", "asc")->get();
		$safetySecurity = $this->opages["backgrounds"]->images()->orderBy("order", "asc")->where("title", "Safety")->first();
		// Prep view data
		$this->viewdata["staff"] = $staff;
		$this->viewdata["students"] = $students;
		$this->viewdata["safetySecurity"] = $safetySecurity;
		$this->viewdata["events"] = $events;
		$this->viewdata["header"] = $header;
		$this->viewdata["page"] = $page;
		$this->viewdata["pagedata"] = $this->getContentdata($page->slug);
		// Serve view
		return View::make('pages.about_us.about_us', $this->viewdata);
	}

	/**
	 * Serve's team page
	 * @return view
	*/
	public function management()
	{
		$page = $this->getPage("management");
		$header = $page->headers()->first();
		// Get management data
		$management = Staff::orderBy("pos", "asc")->get();
		// Prep view data
		$this->viewdata["management"] = $management;
		$this->viewdata["header"] = $header;
		$this->viewdata["page"] = $this->getPage("about-us", "page");
		$this->viewdata["pagedata"] = $this->getContentdata($page->slug);
		// Serve view
		return View::make('pages.about_us.management', $this->viewdata);
	}


	/**
	 * Serve's students page
	 * @return view
	*/
	public function students()
	{
		$page = $this->getPage("students-page-list");
		$this->opages["students"] = $this->getPage("students-slider");
		$header = $page->headers()->first();
		$students = $this->opages["students"]->images()->orderBy("order", "asc")->get();
		// Prep view data
		$this->viewdata["students"] = $students;
		$this->viewdata["page"] = $this->getPage("about-us", "page");
		$this->viewdata["pagedata"] = $this->getContentdata($page->slug);
		$this->viewdata["studentsPageList"] = $this->getContentdata("students-page-list");
		// Serve view
		return View::make('pages.about_us.students', $this->viewdata);
	}


	/**
	 * Serve's the home page
	 * @return view
	*/
	public function calendar()
	{
		$page = $this->getPage("calendar", "page");
		$group = $this->getPage("calendar", "group");
		$this->opages["backgrounds"] = $this->getPage("backgrounds");
		$firstDay = date("Y-m-")."01 00:00:00";
		$lastDay = date("Y-m-t")." 23:59:59";
		$now = date("Y-m-d h:i:s");
		$calendar = new Calendar;
		$calendar = $calendar->make();
		$events = Schoolcalendar::orderBy("schedule_starts", "asc")
								->where("schedule_starts", ">=", $firstDay)
								->where("schedule_starts", "<=", $lastDay)
								->with(["category"=>function($query){
											$query->where("type", "calendar");
										}])
								->get();
		$nextEvent = Schoolcalendar::where("schedule_starts", ">", $now)
									->orderBy("schedule_starts", "asc")
									->first();
		// Get page images
		$images = ($group) ? $group->images()->get() : [];
		$imagesArr = [];
		foreach($images as $image){
			$imagesArr[$image->title] = $image;
		};
		$this->viewdata["pageImages"] = $imagesArr;
		// Prep view data
		$this->viewdata["events"] = $events;
		$this->viewdata["page"] = $this->getPage("about-us", "page");
		$this->viewdata["nextEvent"] = $nextEvent;
		$this->viewdata["calendar"] = $calendar;
		// Serve view
		return View::make('pages.about_us.calendar', $this->viewdata);
	}

}
