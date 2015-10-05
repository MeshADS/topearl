<?php
use Ao\Data\Models\Basicdata;
use Ao\Data\Models\Images;
use Ao\Data\Models\Contentdata;
use Ao\Data\Models\Datagroups;
use Ao\Data\Models\Menus;
use Ao\Data\Models\User;
class SiteController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Site Controller
	|--------------------------------------------------------------------------
	|
	| This works as a base controller for
	| all frontend controllers
	|
	*/

	/*
	** @var $viewdata (array)
	*/
	protected $viewdata = [];
 
	/*
	** @var $site_message (array)
	*/
	protected $site_message = [];

	/**
	 * @var Basic data
	 * Contains basic data
	 */
	protected $basicdata = null;

	/**
	 * @var Model
	 * Serves as controller model
	 */
	protected $model = null;

	/**
	 * @var opages
	 * Contains an array of other pages
	 */
	protected $opages = [];

	/**
	 * @var Flickr Settings
	 * Contains flickr API settings
	 */
	protected $flickr_settings = [
						"key"=>"9d2e8c5dd87e7d18154f80cbbed05511",
						"secrete" => "29e2c5ccfdae5c20",
						"client_id" => "134283513@N04",
						];

	public function __construct(){
		$this->viewdata["basicdata"] = $this->setBasicData();
		$this->viewdata["sitedata"] = $this->getContentdata(["footer", "header"]);
		$this->viewdata["page"] = "";
		$this->viewdata["pagedata"] = [];
		if(Sentry::check()){
			$user = Sentry::getUser();
			$user = User::with(["phone","phonenumbers"])->find($user->id);
			$user->unreadMessages = $user->messages()->wherePivot("state", "=", 0)->count();
			$user->messagesCount = $user->messages()->count();
			$user->outboxCount = $user->outbox()->count();
			$user->awardsCount = $user->awards()->count();
			$user->programsCount = $user->programs()->count();
			$user->resultsCount = $user->results()->count();
			$this->viewdata["userdata"] = $user;
		}

	}

	/**
	 * Sets basic data
	 * @return Basi data Object
	 */
	protected function setBasicData()
	{
		if (Session::has('system_message')) {
			$system_message = Session::get('system_message');
			if ($system_message["access"] == "site") {
				$this->viewdata["view_message"] = $system_message;
			}
		}
		$this->setDefaultImages();
		$this->getMenu();
		return Basicdata::first();
	}

	/**
	 * Get object of page
	 * @return Object
	 */
	protected function getPage($slug, $type = FALSE)
	{
		// Fetch group
		$group = Datagroups::where("slug", $slug);
		// Check if type is specified
			if ($type) {
				// Specify type
				$group = $group->where("type", $type);
			}
		// Get first found record
		$group = $group->first();
		// Return information
		return $group;
	}

	/**
	 * Sets default images
	 * @return none
	 */
	private function setDefaultImages()
	{
		// get header images
		$headerGroup = $this->getPage("header", "group");
		$headerImages = ($headerGroup) ? $headerGroup->image()->get() : [];
		$headerImagesArr = [];
		foreach ($headerImages as $headerImage) {
			$headerImagesArr[$headerImage->title] = $headerImage;
		}
		$this->viewdata["headerImages"] = $headerImagesArr;
		// get menu images
		$menuGroup = $this->getPage("menu", "group");
		$menuImages = ($menuGroup) ? $menuGroup->image()->get() : [];
		$menuImagesArr = [];
		foreach ($menuImages as $menuImage) {
			$menuImagesArr[$menuImage->title] = $menuImage;
		}
		$this->viewdata["menuImages"] = $menuImagesArr;
		// get footer images
		$footerGroup = $this->getPage("footer", "group");
		$footerImages = ($footerGroup) ? $footerGroup->image()->get() : [];
		$footerImagesArr = [];
		foreach ($footerImages as $footerImage) {
			$footerImagesArr[$footerImage->title] = $footerImage;
		}
		$this->viewdata["footerImages"] = $footerImagesArr;
	}

	/**
	 * Sets up menu bar data
	 * @return none
	 */
	private function getMenu()
	{
		// Get models
		$list = Menus::orderBy("position", "asc")->with(["submenus"=>function($query){
			$query->orderBy("position", "asc");
		}])->where("isslave", 0)->get();
		$this->viewdata["menus"] = $list;
	}

	/**
	 * Gets content data for page
	 * @return object
	 */
	protected function getContentdata($slug)
	{
		$content = [];

		if (!is_array($slug)) {
			// Get page
			$page = $this->getPage($slug, "page");
			// Get content
			$data = $page->content()->get();
			$content[$slug] = [];
			// Transform content
			foreach ($data as $dta) {
				$content[$slug][Slugify::slugify($dta->title, "_")] = $this->transformContentData($dta);
			}
			// Return content
			return $content;
		}
		foreach ($slug as $g) {
			// Get page
			$page = $this->getPage($g, "page");
			// Set key for current content
			$content[$g] = [];
			// Get content
			if (count($page) > 0) {
				$data = $page->content()->get();
				// Transform content
				foreach ($data as $dta) {
					$content[$g][Slugify::slugify($dta->title, "_")] = $this->transformContentData($dta);
				}
			}
		}
		// Return content
		return $content;
	}

	/**
	 * Transforms content data
	 * @return array
	*/
	private function transformContentData($data)
	{
		$link = strpos($data->title, '*L*');
		$linkExt = strpos($data->title, '*LX*');
		$findQuery = strpos($data->title, '***');
		$transformed = [];
		$formatted = str_replace("///", "<br>", (strip_tags($data->body)));
		$formatted = str_replace("//b/", "</strong>", $formatted);
		$formatted = str_replace("/b/", "<strong>", $formatted);
		$formatted = preg_replace("/\/a\/(.*)\/\/a\//i", "$1", $formatted);
		$ex_formatted = explode("<br>", $formatted);
		if (count($ex_formatted) > 1) {
			$br_formatted = "";
			foreach ($ex_formatted as $frmt) {
				$br_formatted .= "<div class='col-md-6'><p class='s-text'>".trim($frmt)."</p></div>";
			}
			$formatted = $br_formatted;
		}
		else{
			$formatted = "<div class='col-md-12'><p class='s-text'>".$formatted."</div>";
		}
		$formatted = str_replace('\n', '<br>', $formatted);
		// Default transform
		$transformed = [
			"type" => "default",
			"body" => $data->body,
			"body_notags" => strip_tags($data->body),
			"body_formatted" => $formatted,
			"title" => $data->title,
			"slug" => $data->slug,
		];
		// Links transform
		if ($link !== false) {
			$transformed = [
				"type" => "link",
				"description" => str_replace("*L*", "", $data->title),
				"data" => URL::to(strip_tags($data->body))
			];
		}
		// External links transform
		if ($linkExt !== false) {
			$transformed = [
				"type" => "link",
				"description" => str_replace("*LX*", "", $data->title),
				"data" => $data->body
			];
		}
		// Find query trnsform
		if ($findQuery !== false) {
			$line = explode(";", strip_tags($data->body));
			$transformed = [
				"type" => "query",
				"description" => str_replace("***", "", $data->title),
				"data" => []
			];
			if (count($line) > 0) {
				foreach ($line as $l) {
					// Disect line
					$q = explode("*-*", $l);
					$table = trim($q[0]);
					$q2 = explode("=", $q[1]);
					$col = trim($q2[0]);
					$val = $q2[1];
					// Query selected table
					$query = DB::table($table)->where($col, $val)->get();
					// Store in transformed arrat
					$transformed["data"][] = $query;
				}
			}
			else{
				$line = strip_tags($data->body);
				// Disect line
				$q = explode("*>", $line);
				$table = trim($q[0]);
				$q2 = explode("=", $q[1]);
				$col = trim($q2[0]);
				$val = $q2[1];
				// Query selected table
				$query = DB::table($table)->where($col, $val)->get();
				// Store in transformed arrat
				$transformed["data"][] = $query;
			}
		}
		// Return response
		return $transformed;
	}

	public function getPlusCount($url)
	{
		if(App::environment() == "local") return 0;
		$contents = file_get_contents( 
        'https://plusone.google.com/_/+1/fastbutton?url=' 
        . urlencode($url) 
	    );

	    preg_match( '/window\.__SSR = {c: ([\d]+)/', $contents, $matches );

	    if( isset( $matches[0] ) ) 
	        return (int) str_replace( 'window.__SSR = {c: ', '', $matches[0] );
	    return 0; 
	}

	public function getTwitterCount($url)
	{
		if(App::environment() == "local") return 0;
		// Create tiny URL
		$curl = curl_init(); 
	    $timeout = 5; 
	    curl_setopt($curl, CURLOPT_URL, 'http://urls.api.twitter.com/1/urls/count.json?url='.$url); 
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
	    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout); 
	    $count = curl_exec($curl); 
	    curl_close($curl);
	    $count = json_decode($count);
	    return $count->count;
	}

	public function getFacebookCount($url)
	{
		if(App::environment() == "local") return 0;
		// Create tiny URL
		$curl = curl_init(); 
	    $timeout = 5; 
	    curl_setopt($curl, CURLOPT_URL, 'http://graph.facebook.com/'.urlencode($url)); 
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
	    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout); 
	    $curlResp = curl_exec($curl); 
	    curl_close($curl);
	    $curlResp = json_decode($curlResp);
	    if(@$curlResp->shares) return $curlResp->shares;
	    return 0;
	}

	public function readableNumber($number)
	{
		$period = [".",""];
		// Check number string length
		if (strlen($number) > 3) {
			// Get number length
			$length = strlen($number);
			// Get number scale, substr length and substr limit
			$number_scale = ($length > 9 ? "b" : ($length > 6 ? "m" : "k"));
			$substr_length = ($number_scale == 'k' ? 3 : ($number_scale == 'm' ? 6 : 9));
			$substr_limit = $length - $substr_length;
			// Get first_number
			$first_number = substr($number, 0, $substr_limit);
			$second_number = substr($number, $substr_limit, 1);
			// Format number
			$number = $first_number.($second_number > 0 ? ".":"").($second_number > 0 ? $second_number:"").$number_scale;
		}
		// Return result
	    return $number;
	}

}