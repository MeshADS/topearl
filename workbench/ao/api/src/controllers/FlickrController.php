<?php namespace Ao\Api\Controllers;

class FlickrController extends ApiController{

	private $settings = [
						"key"=>"9d2e8c5dd87e7d18154f80cbbed05511",
						"secrete" => "29e2c5ccfdae5c20",
						"client_id" => "134283513@N04",
						];

	public function get()
	{
		// Get cURL resource
		$curl = curl_init();
		// Set some options - we are cancelling SSL cert verification here
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => 'https://api.flickr.com/services/rest/?method=flickr.photosets.getList&api_key='.$this->settings["key"].'&user_id='.$this->settings["client_id"].'&per_page=12&format=json&nojsoncallback=1',
		    CURLOPT_SSL_VERIFYPEER => false
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		// Close request to clear up some resources
		curl_close($curl);
		// Validate response
		if (!$resp){
			// Return response
			return \Response::json(["status"=>"error", "level"=>"danger", "messgae"=>"Photo stream is currently unavailable!"], 200);
		};
		$photos = [];
		// Decode json response
		$object = json_decode( $resp );
		foreach ($object->photosets->photoset as $set) {
			// Get cURL resource
			$curl = curl_init();
			// Set some options - we are cancelling SSL cert verification here
			curl_setopt_array($curl, array(
			    CURLOPT_RETURNTRANSFER => 1,
			    CURLOPT_URL => 'https://api.flickr.com/services/rest/?method=flickr.photosets.getPhotos&api_key='.$this->settings["key"].'&user_id='.$this->settings["client_id"].'&per_page=12&photoset_id='.$set->id.'&format=json&nojsoncallback=1',
			    CURLOPT_SSL_VERIFYPEER => false
			));
			// Send the request & save response to $resp
			$resp = curl_exec($curl);
			// Close request to clear up some resources
			curl_close($curl);
			// Validate response
			if (!$resp){
				// Return response
				return \Response::json(["status"=>"error", "level"=>"danger", "messgae"=>"Photo stream is currently unavailable!"], 200);
			};
			$resp = json_decode( $resp ); // stdClass object
			foreach($resp->photoset->photo as $photo){
				$photo->set = $set->id;
				$photos[] = $photo;

				if (count($photos) > 11) {

					break;

				} 
			}

			if (count($photos) > 11) {
				break;
			} 

		}
		// Return response
		return \Response::json(["status"=>"success", "level"=>"success", "messgae"=>"Success!", "data"=>$photos], 200);
	}

}