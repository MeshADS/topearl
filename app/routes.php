<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get("/", "PagesController@home");
Route::get("about_us/", "AboutController@about_us");
Route::get("about_us/management", "AboutController@management");
Route::get("about_us/students", "AboutController@students");
Route::get("about_us/calendar", "AboutController@calendar");
Route::get("about_us/calendar/{category}", "AboutController@calendar");
Route::get("admission", "AdmissionController@index");
Route::get("form/{form}", "FormController@show");
Route::post("form/{form}", "FormController@store");
Route::get("download/file/{key}", "RequestController@downloadFile");
// Route::get("admission/form", "AdmissionController@form");
// Route::get("admission/afterschool", "AdmissionController@afterschool");
Route::get("contact_us", "PagesController@contact_us");
Route::get("programs", "PagesController@programs");
Route::get("news", "NewsController@index");
Route::get("news/categories", "NewsController@categories");
Route::get("news/in/{in}", "NewsController@category");
Route::get("news/{slug}", "NewsController@read");
Route::get("gallery", "GalleryController@index");
Route::get("gallery/{id}", "GalleryController@show");
Route::get("auth/login/", ["before"=>"auth.in", "uses" => "AuthController@login"]);
Route::get("auth/forgotPassword", ["before"=>"auth.in", "uses" => "AuthController@forgot"]);
Route::get("auth/reset/{key}", ["before"=>"auth.in", "uses" => "AuthController@reset"]);
Route::get("myaccount/logout", function(){
	if (!Sentry::check()) {
		// Flash system message
		App::abort(404);
	}
	Sentry::logout();
	// Flash system message
	Session::flash("system_message", ["level"=>"success", "type"=>"page", "access"=>"site", "message"=>"<i class='fa fa-check'></i>&nbsp;You've successfully logged out!"]);
	// Return to / page
	return Redirect::to("/");
});
Route::group(["prefix"=>"myaccount", "before"=>"auth"], function(){
	Route::get("/", "MyaccountController@index");
	Route::get("programs", "MyaccountController@programs");
	Route::get("results", "ResultsController@index");

	// Messages route
	Route::get("messages", "MessagesController@index");
	Route::put("messages/mark/{action}", ["before"=>"csrf", "uses"=>"MessagesController@mark"]);
	Route::get("messages/{id}", "MessagesController@show");

	// Settings route
	Route::get("settings", "SettingsController@index");
	Route::put("settings", ["before"=>"csrf", "uses"=>"SettingsController@updateInfo"]);
	Route::put("settings/email", ["before"=>"csrf", "uses"=>"SettingsController@changeEmail"]);
	Route::put("settings/password", ["before"=>"csrf", "uses"=>"SettingsController@changePassword"]);

	// Contact List
	Route::get("contact-list", "ContactlistController@index");
	Route::post("contact-list", ["before"=>"csrf", "uses"=>"ContactlistController@save"]);
	Route::put("contact-list/{id}", ["before"=>"csrf", "uses"=>"ContactlistController@update"]);
	Route::delete("contact-list/{id}", ["before"=>"csrf", "uses"=>"ContactlistController@destroy"]);

	// Awards route
	Route::get("awards", "AwardsController@index");
	Route::get("awards/download/{id}", "AwardsController@download");
});

Route::post("admission/form", ["before"=>"csrf", "uses"=>"AdmissionController@apply"]);
Route::post("admission/afterschool", ["before"=>"csrf", "uses"=>"AdmissionController@afterschool_apply"]);
Route::post("contact_us/send", ["before"=>"csrf", "uses"=>"RequestController@sendMessage"]);
Route::post("programs/apply", ["before"=>"csrf", "uses"=>"RequestController@apply"]);