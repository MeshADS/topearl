<?php
use Ao\Data\Models\Files;
use Ao\Data\Models\Basicdata;
class MyaccountController extends SiteController {

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
		// Prep view data
		$this->viewdata["current_menu"] = 1;
		$this->viewdata["header"] = $header;
		$this->viewdata["pageslug"] = $page->slug;
		$this->viewdata["page"] = $page;
		$this->viewdata["pagedata"] = $this->getContentdata($page->slug);
		// Serve view
		return View::make('pages.myaccount.index', $this->viewdata);		
	}

	public function programs()
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
		// Prep view data
		$this->viewdata["current_menu"] = 2;
		$this->viewdata["list"] = $this->viewdata["userdata"]->programs()->paginate(20);
		$this->viewdata["header"] = $header;
		$this->viewdata["pageslug"] = $page->slug;
		$this->viewdata["page"] = $page;
		$this->viewdata["pagedata"] = $this->getContentdata($page->slug);
		// Serve view
		return View::make('pages.myaccount.programs', $this->viewdata);	
	}

}