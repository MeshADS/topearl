<?php 
	use Ao\Data\Models\Posts;
	use Ao\Data\Models\Headers;
	use Guzzle\Http\Client;

	class GalleryController extends SiteController{

		public function __construct(Posts $model)
		{
			$this->model = $model;
			$page = $this->getPage("gallery");
			$this->viewdata["basicdata"] = $this->setBasicData();
			$this->viewdata["sitedata"] = $this->getContentdata(["Footer"]);
			$this->viewdata["page"] = $page;
			$this->viewdata["pagedata"] = [];
			$this->viewdata["header"] = $page->headers()->first();
		}

		public function index()
		{
			// Retrieve flickr settings
			$page = Input::get("page", 1);
			// Prep request data
			$params = '?method=flickr.photosets.getList&api_key='.$this->flickr_settings['key'].'&user_id='.$this->flickr_settings['client_id'].'&per_page=20&page='.$page.'&format=json&nojsoncallback=1';
			// Get cURL resource
			$curl = curl_init();
			// Set some options - we are cancelling SSL cert verification here
			curl_setopt_array($curl, array(
			    CURLOPT_RETURNTRANSFER => 1,
			    CURLOPT_URL => 'https://api.flickr.com/services/rest'.$params,
			    CURLOPT_SSL_VERIFYPEER => false
			));
			// Send the request & save response to $resp
			$response = curl_exec($curl);
			// Close request to clear up some resources
			curl_close($curl);
			// decode json response
			$response = json_decode( $response ); // stdClass object
			// return $response;
			$this->viewdata["list"] = $response->photosets;
			return View::make("pages.gallery.list", $this->viewdata);
		}

		public function show($id)
		{
			$page = Input::get('page', 1);
			// Get cURL resource
			$curl = curl_init();
			// Set some options - we are cancelling SSL cert verification here
			curl_setopt_array($curl, array(
			    CURLOPT_RETURNTRANSFER => 1,
			    CURLOPT_URL => 'https://api.flickr.com/services/rest/?method=flickr.photosets.getPhotos&api_key='.$this->flickr_settings['key'].'&user_id='.$this->flickr_settings['client_id'].'&per_page=20&page='.$page.'&format=json&nojsoncallback=1&photoset_id='.$id,
			    CURLOPT_SSL_VERIFYPEER => false
			));
			// Send the request & save response to $resp
			$resp = curl_exec($curl);
			// Validate response
			if (!$resp){
				// Abort app
				return App::abort(404);
			};
			// Close request to clear up some resources
			curl_close($curl);
			// decode json response
			$object = json_decode( $resp ); // stdClass object
			// Prep view data
			$this->viewdata["album"] = $object->photoset;
			// Serve view
			return View::make("pages.gallery.show", $this->viewdata);
		}

	}