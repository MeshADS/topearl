<?php namespace Ao\Api\Controllers;
use Ao\Data\Models\User;

class UserController extends ApiController{

	public function upload()
	{
		// Get request data
		$data = \Input::all(); 
		// Valudation rules
		$rules = [ 
					"img" => "mimes:jpeg,png|max:1200|required",
					"user" => "required|exists:tk_users,id"
				];
		// Validation messages
		$messages = [ 
					"img.mimes" => "Invalid file format, file must be of the format jpeg, jpg or png.",
					"img.max" => "Maximum file size of 1200kb exceeded.",
					"img.required" => "Please select an image to upload",
					"user.required" => "User not specified.",
					"user.exists" => "The specified user doesn't exist."
				];
		// Validation
		$validation = \Validator::make($data, $rules, $messages);
		// Check validation
		if ($validation->fails()) {
			$message = "";
			$errors = $validation->errors()->getMessages();
			foreach($errors as $error){
				for ($i=0; $i < count($error); $i++) { 
					$message .= $error[$i]." ";
				}
			}
			$response_data = json_encode(['status' => 'error', 'level'=>'danger', 'message'=> $message]);
			return \Response::json($response_data, 200);
		}
		// Upload file
		$file = \Input::file('img');
		$ext = $file->getClientOriginalExtension();
		$name = md5($file->getClientOriginalName().$data['user']);
		$size = $file->getSize();
		$mime = $file->getMimeType();
		$image = (string) \Image::make($file)->encode($ext);
		$image = "data:".$mime.";base64,".base64_encode($image);
		list($width, $height) = getimagesize($image);
		// Prep response data
		$response_data = [
			"status" => "success",
			"level" => "success",
			"message" => "File uploaded.",
			"url" => $image,
			"width" => $width,
			"height" => $height
		];
		$response_data = json_encode($response_data);
		// Return json response
		return \Response::json($response_data, 200);
	}

	public function crop()
	{
		// Get request data
		$data = \Input::all(); 
		// Valudation rules
		$rules = [ 
					"imgUrl" => "required",
					"user" => "required|exists:tk_users,id"
				];
		// Validation messages
		$messages = [ 
					"imgUrl.required" => "Please select an image to upload.",
					"user.required" => "User not specified.",
					"user.exists" => "The specified user doesn't exist."
				];
		// Validation
		$validation = \Validator::make($data, $rules, $messages);
		// Check validation
		if ($validation->fails()) {
			$message = "";
			$errors = $validation->errors()->getMessages();
			foreach($errors as $error){
				for ($i=0; $i < count($error); $i++) { 
					$message .= $error[$i]." ";
				}
			}
			$response_data = json_encode(['status' => 'error', 'level'=>'danger', 'message'=> 'Invalid file format, file must be of the format jpeg, jpg or png.'],200);
			return \Response::json($response_data, 200);
		}
		$user = User::find($data["user"]);
		// Get file
		$file = \Input::get('imgUrl');
		$allowed = [
					["mime" => "image/png", "isThis" => null, "ext" => "png"],
					["mime" => "image/jpg", "isThis" => null, "ext" => "jpg"],
					["mime" => "image/jpeg", "isThis" => null, "ext" => "jpeg"]
				];
		$file_type = null;
		foreach($allowed as $key => $type){
			$pos = strpos($file, $type['mime']);
			if ($pos) {
				$type["isThis"] = true;
			}
			$file_type = $type;
		}
		if (is_null($file_type)) {
			// File format not correct
			// Return json response
			$response_data = json_encode(['status' => 'error', 'level'=>'danger', 'message'=> 'Invalid file format, file must be of the format jpeg, jpg or png.']);
			return \Response::json($response_data, 200);
		}
		// Process file
		$processed_file = str_replace("data:".$file_type["mime"].";base64,", "", $file);
		// Upload file
		$y = \Input::get('imgY1');
		$x = \Input::get('imgX1');
		$cropH = \Input::get('cropH');
		$cropW = \Input::get('cropW');
		$imgW = \Input::get('imgW');
		$imgH = \Input::get('imgH');
		$path = "data/img/".md5(time()."Users".$data["user"]).".".$file_type["ext"];
		$image = (string) \Image::make($processed_file)->resize($imgW, $imgH)->encode($file_type["ext"]);
		$image = base64_encode($image);
		$image = \Image::make($image, 75)->crop($cropW, $cropH, $x, $y)->save($path);
		// Delete current avatar
		if (\File::exists($user->avatar)) {
			\File::delete($user->avatar);
		}
		// Update user info
		$user->update(["avatar"=>$path]);
		// Prep response data
		$response_data = [
			"status" => "success",
			"level" => "success",
			"message" => "Image successfully cropped!",
			"url" => $path,
		];
		// 
		$response_data = json_encode($response_data);
		// Return json response
		return \Response::json($response_data, 200);	
	}

}