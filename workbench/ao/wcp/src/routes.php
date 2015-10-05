<?php 

	Route::group(["prefix"=>"admin"], function(){

		Route::get("logout", function(){
			// Log user out
			Sentry::logout();
			// Flash system message
			Session::flash("system_message",["level"=>"success", "access"=>"wcp", "type" => "login", "message" => "You've logged out!"]);			
			// Return a response			
			return Redirect::to('admin/login');
		});

		// Home routes
		Route::get("/", "Ao\Wcp\Controllers\HomeController@index");
		// Forms routes
		Route::delete("forms/bulk", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\FormsController@bulkDestroy"]);
		Route::get("forms/{id}/elements", "Ao\Wcp\Controllers\FormsController@elements");
		Route::get("forms/{id}/submitions", "Ao\Wcp\Controllers\FormsController@submitions");
		Route::post("forms/{id}/elements/{type}", "Ao\Wcp\Controllers\FormsController@createElements");
		Route::delete("forms/{id}/elements/bulk", "Ao\Wcp\Controllers\FormsController@bulkDestroyElements");
		Route::delete("forms/{id}/elements/{eid}", "Ao\Wcp\Controllers\FormsController@destroyElement");
		Route::delete("forms/{id}/submitions/bulk", "Ao\Wcp\Controllers\FormsController@bulkDestroySubmitions");
		Route::delete("forms/{id}/submitions/{sid}", "Ao\Wcp\Controllers\FormsController@destroySubmition");
		Route::put("forms/{id}/elements/{eid}", "Ao\Wcp\Controllers\FormsController@updateElement");
		Route::put("forms/{id}/submition/{sid}", "Ao\Wcp\Controllers\FormsController@updateElement");
		Route::resource("forms", "Ao\Wcp\Controllers\FormsController");
		// Menu builder routes
		Route::delete("menubuilder/bulk", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\MenubuilderController@bulkDestroy"]);
		Route::get("menubuilder/{id}/submenus", "Ao\Wcp\Controllers\MenubuilderController@show");
		Route::resource("menubuilder", "Ao\Wcp\Controllers\MenubuilderController");
		// Programs routes
		Route::delete("programs/bulk", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\ProgramsController@bulkDestroy"]);
		Route::resource("programs", "Ao\Wcp\Controllers\ProgramsController", ["except"=>["show"]]);
		// File manager routes
		Route::delete("filemanager/bulk", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\FilemangerController@bulkDestroy"]);
		Route::get("filemanager/api", ["before"=>"auth.api", "uses"=>"Ao\Wcp\Controllers\FilemangerController@api"]);
		Route::get("filemanager/{id}/submenus", "Ao\Wcp\Controllers\FilemangerController@show");
		Route::resource("filemanager", "Ao\Wcp\Controllers\FilemangerController");
		// Categories routes
		Route::delete("categories/bulk", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\CategoriesController@bulkDestroy"]);
		Route::resource("categories", "Ao\Wcp\Controllers\CategoriesController");
		// Icons routes
		Route::delete("aoicons/bulk", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\IconsController@bulkDestroy"]);
		Route::resource("aoicons", "Ao\Wcp\Controllers\IconsController");
		// Classes routes
		Route::delete("classes/bulk", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\ClassesController@bulkDestroy"]);
		Route::resource("classes", "Ao\Wcp\Controllers\ClassesController");
		// Contact data routes
		Route::delete("contact_data/bulk", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\ContactdataController@bulkDestroy"]);
		Route::resource("contact_data", "Ao\Wcp\Controllers\ContactdataController");
		// Headers routes
		Route::delete("headers/bulk", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\HeadersController@bulkDestroy"]);
		Route::resource("headers", "Ao\Wcp\Controllers\HeadersController");
		// Image routes
		Route::delete("images/bulk", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\ImagesController@bulkDestroy"]);
		Route::resource("images", "Ao\Wcp\Controllers\ImagesController");
		// Content data routes
		Route::delete("content_data/bulk", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\ContentdataController@bulkDestroy"]);
		Route::resource("content_data", "Ao\Wcp\Controllers\ContentdataController");
		// Staff routes
		Route::delete("staff/bulk", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\StaffController@bulkDestroy"]);
		Route::resource("staff", "Ao\Wcp\Controllers\StaffController");
		// Gallery routes
		Route::delete("gallery/bulk", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\GalleryController@bulkDestroy"]);
		Route::delete("gallery/bulk/{id}", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\GalleryController@bulkDestroyPhotos"]);
		Route::delete("gallery/{id}/{id2}", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\GalleryController@destroyPhoto"]);
		Route::put("gallery/{id}/{id2}", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\GalleryController@updatePhoto"]);
		Route::post("gallery/{id}/upload", "Ao\Wcp\Controllers\GalleryController@upload");
		Route::get("gallery/{id}/upload", "Ao\Wcp\Controllers\GalleryController@edit");
		Route::resource("gallery", "Ao\Wcp\Controllers\GalleryController");
		// Staff routes
		Route::post('calendar/{id}', ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\CalendarController@update"]);
		Route::post('calendar/{id}/delete', ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\CalendarController@destroy"]);
		Route::resource("calendar", "Ao\Wcp\Controllers\CalendarController");
		// Posts routes
		Route::delete("posts/bulk", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\PostsController@bulkDestroy"]);
		Route::resource("posts", "Ao\Wcp\Controllers\PostsController");
		Route::group(["prefix"=>"posts/{id}/comments"], function(){
			// Comment routes
			Route::get("", "Ao\Wcp\Controllers\PostsController@comments");
			Route::post("", "Ao\Wcp\Controllers\PostsController@storecomment");
			Route::put("{id2}", "Ao\Wcp\Controllers\PostsController@updatecomment");
			Route::delete("bulk", "Ao\Wcp\Controllers\PostsController@destroycomments");
			Route::delete("{id2}", "Ao\Wcp\Controllers\PostsController@destroycomment");
		});
		// Admission routes
		Route::delete("admission/bulk", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\AdmissionController@bulkDestroy"]);
		Route::resource("admission", "Ao\Wcp\Controllers\AdmissionController");
		Route::group(["prefix"=>"admission/{id}/list"], function(){
			// Comment routes
			Route::put("{id2}", "Ao\Wcp\Controllers\AdmissionController@updateitem");
			Route::delete("bulk", "Ao\Wcp\Controllers\AdmissionController@bulkDestroyItems");
			Route::delete("{id2}", "Ao\Wcp\Controllers\AdmissionController@destroyitem");
		});
		// Accounts routes
		Route::put("accounts/{id}/change_password", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\AccountsController@change_password"]);
		Route::put("accounts/{id}/activate", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\AccountsController@activate"]);

		Route::post("accounts/{id}/message", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\AccountsController@message"]);
		Route::post("accounts/{id}/mail", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\AccountsController@sendmail"]);
		Route::post("accounts/{id}/text", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\AccountsController@sendtext"]);

		Route::post("accounts/{id}/programs", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\AccountsController@addToPrograms"]);
		Route::delete("accounts/{id}/programs/{program}", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\AccountsController@removeProgram"]);
		
		Route::post("accounts/{id}/phonenumber", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\AccountsController@phonenumber"]);
		Route::put("accounts/{id}/phonenumber/{phonenumber}", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\AccountsController@updatePhone"]);
		Route::delete("accounts/{id}/phonenumber/{phonenumber}", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\AccountsController@destroyPhone"]);
		
		Route::post("accounts/{id}/awards", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\AccountsController@award"]);
		Route::put("accounts/{id}/awards/{award}", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\AccountsController@updateAward"]);
		Route::delete("accounts/{id}/awards/{award}", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\AccountsController@destroyAward"]);
		
		Route::post("accounts/{id}/results", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\AccountsController@result"]);
		Route::put("accounts/{id}/results/{result}", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\AccountsController@updateResult"]);
		Route::delete("accounts/{id}/results/{result}", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\AccountsController@destroyResult"]);
		
		Route::delete("accounts/bulk", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\AccountsController@bulkDestroy"]);
		Route::resource("accounts", "Ao\Wcp\Controllers\AccountsController");
		// Datagroups creator routes
		Route::delete("datagroups/bulk", ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\DatagroupsController@bulkDestroy"]);
		Route::resource("datagroups", "Ao\Wcp\Controllers\DatagroupsController");
		// Settings routes
		Route::get("basic_info", "Ao\Wcp\Controllers\SettingsController@basic_info");
		Route::put("basic_info/{id}", "Ao\Wcp\Controllers\SettingsController@basic_info_edit");
		Route::put("basic_info/{id}/logo", "Ao\Wcp\Controllers\SettingsController@basic_info_logo");
		// Authentication routes
		Route::get('login', "Ao\Wcp\Controllers\AuthController@showLogin");
		Route::post('login', ["before"=>"csrf", "uses"=>"Ao\Wcp\Controllers\AuthController@login"]);
		Route::get("oath/", "Ao\Wcp\Controllers\OauthController@oath");
		Route::get("oath/flickr", "Ao\Wcp\Controllers\OauthController@flickr");

	});