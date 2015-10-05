<?php 

	Route::group(["prefix"=>"api", "before"=>"auth.api"], function(){

		Route::get("calendar", "Ao\Api\Controllers\CalendarController@index");
		Route::get("twitter", "Ao\Api\Controllers\TwitterController@index");
		Route::get("flickr", "Ao\Api\Controllers\FlickrController@get");
		Route::get("filemanager", "Ao\Api\Controllers\FilemangerController@index");
		Route::post("user/uploadAvatar", "Ao\Api\Controllers\UserController@upload");
		Route::post("user/cropAvatar", "Ao\Api\Controllers\UserController@crop");
		Route::post("auth/login", "Ao\Api\Controllers\AuthController@login");
		Route::post("auth/forgot", "Ao\Api\Controllers\AuthController@forgot");
		Route::post("auth/reset/{key}", "Ao\Api\Controllers\AuthController@reset");

	});