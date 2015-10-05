<?php namespace Ao\Api\Controllers;
use \Twitter;

class TwitterController extends ApiController{

	public function index()
	{
		// Fetch userdata
		$user = Twitter::getUsers(["screen_name"=>'topearlng']);
		$user = json_encode($user);
		$user = json_decode($user);
		$userData = [
					"photo" => @$user->profile_image_url,
					"banner" => @$user->profile_banner_url,
					"backgroundImage" => @$user->profile_background_image_url,
					"textColor" => @$user->profile_text_color,
					"linkColor" => @$user->profile_link_color,
					"name" => @$user->name,
					"screenName" => @$user->screen_name,
					"url" => @$user->url,
					"followersCount" => @$user->followers_count,
				];
		// Fetch tweets
		$tweets = Twitter::getUserTimeline(['screen_name' => $user->screen_name, 'count' => 8, 'format' => 'json', 'exclude_replies' => true]);
		$tweets = json_decode($tweets);
		// Transform tweets
		$tweets = $this->transform($tweets);
		// Return response
		return \Response::json(["status"=>"success", "level"=>"success", "messgae"=>"Success!", "data"=>["user"=>$userData, "tweets"=>$tweets]], 200);
	}

	private function transform($tweets)
	{
		$data = [];
		// Loop through tweets
		foreach ($tweets as $tw) {
			$tw->text = Twitter::linkify($tw->text);
			// Add new tweet
			$data[] = [
						"text" => $tw->text,
						"time" => strtotime($tw->created_at),
						"name" => $tw->user->name,
						"username" => $tw->user->screen_name
					];
		}
		// Return data
		return $data;
	}

}