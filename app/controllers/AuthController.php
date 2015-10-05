<?php
class AuthController extends SiteController {

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
	 * Serve's the forgot page
	 * @return view
	*/
	public function login()
	{
		$page = $this->getPage("auth", "page");
		$group = $this->getPage("auth", "group");
		$header = $page->headers()->orderBy("order", "asc")->first();
		// Get page images
		$images = ($group) ? $group->images()->get() : [];
		$imagesArr = [];
		foreach($images as $image){
			$imagesArr[$image->title] = $image;
		};
		$this->viewdata["pageImages"] = $imagesArr;
		// Prep view data
		$this->viewdata["header"] = $header;
		$this->viewdata["pageslug"] = $page->slug;
		$this->viewdata["page"] = $page;
		$this->viewdata["pagedata"] = $this->getContentdata($page->slug);
		// Serve view
		return View::make('pages.auth.login', $this->viewdata);
	}

	/**
	 * Serve's the forgot page
	 * @return view
	*/
	public function forgot()
	{
		$page = $this->getPage("auth", "page");
		$group = $this->getPage("auth", "group");
		$header = $page->headers()->orderBy("order", "asc")->first();
		// Get page images
		$images = ($group) ? $group->images()->get() : [];
		$imagesArr = [];
		foreach($images as $image){
			$imagesArr[$image->title] = $image;
		};
		$this->viewdata["pageImages"] = $imagesArr;
		// Prep view data
		$this->viewdata["header"] = $header;
		$this->viewdata["pageslug"] = $page->slug;
		$this->viewdata["page"] = $page;
		$this->viewdata["pagedata"] = $this->getContentdata($page->slug);
		// Serve view
		return View::make('pages.auth.forgot', $this->viewdata);
	}

	/**
	 * Serve's the reset page
	 * @return view
	*/
	public function reset($key)
	{
		try
		{
		    $user = Sentry::findUserByResetPasswordCode($key);
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    return App::abort(404);
		}
		$page = $this->getPage("auth", "page");
		$group = $this->getPage("auth", "group");
		$header = $page->headers()->orderBy("order", "asc")->first();
		// Get page images
		$images = ($group) ? $group->images()->get() : [];
		$imagesArr = [];
		foreach($images as $image){
			$imagesArr[$image->title] = $image;
		};
		$this->viewdata["pageImages"] = $imagesArr;
		// Prep view data
		$this->viewdata["key"] = $key;
		$this->viewdata["user"] = $user;
		$this->viewdata["header"] = $header;
		$this->viewdata["pageslug"] = $page->slug;
		$this->viewdata["page"] = $page;
		$this->viewdata["pagedata"] = $this->getContentdata($page->slug);
		// Serve view
		return View::make('pages.auth.reset', $this->viewdata);
	}
}
