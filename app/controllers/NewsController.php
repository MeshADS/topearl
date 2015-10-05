<?php 
	use Ao\Data\Models\Posts;
	use Ao\Data\Models\Headers;
	use Ao\Data\Models\Categories;

	class NewsController extends SiteController{

		public function __construct(Posts $model)
		{
			Parent::__construct();
			$page = $this->getPage("news", "page");
			$this->model = $model;
			$this->viewdata["basicdata"] = $this->setBasicData();
			$this->viewdata["sitedata"] = $this->getContentdata(["Footer"]);
			$this->viewdata["page"] = $page;
			$this->viewdata["pagedata"] = [];
			$this->viewdata["latestnews"] = $this->model->orderBy("created_at", "desc")
														->with(["category"=>function($query){
															$query->where("type", "posts");
														}])
														->where("publish_state", 1)
														->take(4)
														->get();

			$this->viewdata["pane_categories"] = Categories::orderBy("created_at", "desc")
											->where("type", "posts")
											->with(["posts"=>function($query){
												$query->where("publish_state", 1)->get();
											}])->take(7)->get();

			$this->viewdata["header"] = $page->headers()->first();
		}

		public function index()
		{
			$list = $this->model->orderBy("created_at", "desc")->with(["category"=>function($query){
										$query->where("type", "posts");
									}])->where("publish_state", 1)->paginate(10);
			$search = Input::get("search", null);
			if (!is_null($search)) {
				$list = $this->model->orderBy("created_at", "desc")
									->where("title", "like", "%".$search."%")
									->orWhere("body", "like", "%".$search."%")
									->with(["category"=>function($query){
										$query->where("type", "posts");
									}])->where("publish_state", 1)->paginate(10);
			}
			
			$this->viewdata["list"] = $list;
			return View::make("pages.news.list", $this->viewdata);
		}

		public function read($slug)
		{
			// Fetch model
			$item = $this->model->where("slug", $slug)->with(["category"=>function($query){
										$query->where("type", "posts");
									}])->where("publish_state", 1)->first();
			// Validate model
			if (!$item) {
				return App::abort(404);
			}
			// Grab social data
			$item->facebookCount = $this->readableNumber($this->getFacebookCount(Request::url()));
			$item->twitterCount = $this->readableNumber($this->getTwitterCount(Request::url()));
			$item->plusCount = $this->readableNumber($this->getPlusCount(Request::url()));
			// Prep view data
			$this->viewdata["item"] = $item;
			// Serve view
			return View::make("pages.news.read", $this->viewdata);
		}

		public function categories()
		{
			$list = Categories::orderBy("created_at", "desc")
								->where("type", "posts")
								->with(["posts"=>function($query){
									$query->where("publish_state", 1)->count();
								}, "post"])->paginate(20);

			$this->viewdata["list"] = $list;

			return View::make("pages.news.categories", $this->viewdata);
		}

		public function category($slug)
		{
			// Fetch category model
			$category = Categories::orderBy("created_at", "desc")->where("slug", $slug)->where("type", "posts")->first();
			// Validate category model
			if (count($category) < 1) {
				return App::abort(404);
			}
			// Fetch posts model
			$list = $this->model->orderBy("created_at", "desc")->with(["category"=>function($query){
										$query->where("type", "posts");
									}])
								->where("publish_state", 1)->where("category_id", $category->id)
								->paginate(10);
			// Prep view data
			$this->viewdata["list"] = $list;
			$this->viewdata["category"] = $category;
			// Serve view
			return View::make("pages.news.category", $this->viewdata);
		}

	}