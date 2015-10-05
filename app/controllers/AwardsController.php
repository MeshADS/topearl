<?php
class AwardsController extends SiteController {

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
		$this->viewdata["current_menu"] = 4;
		$this->viewdata["list"] = $this->viewdata["userdata"]->awards()->paginate(20);
		$this->viewdata["header"] = $header;
		$this->viewdata["pageslug"] = $page->slug;
		$this->viewdata["page"] = $page;
		$this->viewdata["pagedata"] = $this->getContentdata($page->slug);
		// Serve view
		return View::make('pages.myaccount.awards', $this->viewdata);	
	}

	public function download($id)
	{
		// Find award model
		$item = $this->viewdata["userdata"]->awards()->find($id);
		if (!$item) {
			return App::abort(404, "Not found!");
		}
		if (!File::exists($item->file)) {
			return App::abort(404, "Not found!");
		}
		// Download
		return \Response::download($item->file, $item->title.".pdf");
	}

}