<?php namespace Ao\Wcp\Controllers;

use \Input;
use \Redirect;
use \Request;
use \Session;
use Ao\Data\Models\Basicdata;
use Ao\Data\Models\Services;
use \OAuth;
use \OAuth\OAuth1\Service\Flickr;
use \OAuth\ServiceFactory;
use \OAuth\Common\Storage\Session as OSession;
use \OAuth\Common\Consumer\Credentials;
use \OAuth\Common\Http\Client\CurlClient;

class OauthController extends WcpController{

	Private $inputs;

	public function __construct()
	{
		// Authenticate the user
		$this->beforeFilter("auth.wcp");
		// Check for flashed site messages
		if (Session::has('system_message')) {
			$system_message = Session::get('system_message');
			if ($system_message["access"] == "wcp") {
				$this->viewdata["view_message"] = $system_message;
			}
		}
		// Load user data
		$user_data = \Sentry::getUser();
		if(count($user_data) > 0){
			$user_data = \Sentry::findUserById($user_data->id);
			$user_group = $user_data->getGroups();
			$this->viewdata["user_data"] = $user_data;
			$this->viewdata["user_group"] = $user_group[0];
			// Load basic data
			$basic_info = Basicdata::orderBy("created_at", "desc")->first();
			$this->viewdata["basic_info"] = $basic_info;
			// Retrieve request data
			$this->inputs = Input::all();

		}
	}

	public function flickr()
	{
		$serviceCredentials = \Config::get("services.flickr");

		$curl = curl_init();
		$url = "https://www.flickr.com/services/oauth/request_token";
		$url .= "?oauth_nonce=".time().rand(10,1000000);
		$url .= "&oauth_timestamp=".time();
		$url .= "&oauth_consumer_key=".$serviceCredentials["key"];
		$url .= "&oauth_signature_method=".urlencode("HMAC-SHA1");
		$url .= "&oauth_version=".urlencode("1.0");
		$url .= "&oauth_signature=".md5(time().rand(10,1000000).$serviceCredentials["secret"]);
		$url .= "&oauth_callback=".urlencode(Request::url());
		return Redirect::to($url);
		// Set some options - we are cancelling SSL cert verification here
		// curl_setopt_array($curl, array(
		//     CURLOPT_RETURNTRANSFER => 1,
		//     CURLOPT_URL => $url,
		//     CURLOPT_SSL_VERIFYPEER => false
		// ));
		// // Send the request & save response to $resp
		// $resp = curl_exec($curl);
		// // Close request to clear up some resources
		// curl_close($curl);
		// echo $resp;
		// echo "<br><br><br><br>";
		// echo $url_params;

		// $result = json_decode($resp);

		// return $result;

	}
} 

