<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (!Sentry::check())
	{
		// Set default return url
		$return = URL::to("admin");
		if (Request::method("get")) {
			// Get current URL
			$return = Request::url();
		}
		// URL encode return url
		$return = urlencode($return);
		// Set system message
		Session::flash('system_message', ["level"=>"danger", "access"=>"site", "type"=>"page", "message"=>"<i class='fa fa-ban'></i>&nbsp;Please Login to continue!"]);
		// Redirect to login page
		return Redirect::to('auth/login?return='.$return);
	}
	// Check if logged in user is a student
	if (Sentry::check()) {
		try
		{
			// Get logged in user
			$user = Sentry::getUser();
		    // Find the user using the user id
		    $user = Sentry::findUserByID($user->id);
		    // Get administrators
		    $group = Sentry::findGroupByName("Students");
		    // Check if user is in students group
		    if(!$user->inGroup($group)){
		    	// Set system message
				return App::abort(403, "Access denied!!!");
		    }
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    // Set system message
			Session::flash('system_message', ["level"=>"danger", "access"=>"site", "type"=>"page", "message"=>"<i class='fa fa-ban'></i>&nbsp;Please Login to continue!"]);
			// Redirect to login page
			return Redirect::to('auth/login');
		}
	}
});

Route::filter('auth.in', function()
{
	if (Sentry::check())
	{
		// Redirect to /
		return Redirect::to('/');
	}
});

Route::filter('auth.basic', function()
{
	return Auth::basic();
});

Route::filter('auth.wcp', function()
{
	if (!Sentry::check())
	{
		// Set default return url
		$return = URL::to("admin");
		if (Request::method("get")) {
			// Get current URL
			$return = Request::url();
		}
		// URL encode return url
		$return = urlencode($return);
		// Set system message
		Session::flash('system_message', ["level"=>"danger", "access"=>"wcp", "type"=>"login", "message"=>"Please Login to continue!"]);
		// Redirect to login page
		return Redirect::to('admin/login?return='.$return);
	}
	// Check if logged in user is an administrator
	if (Sentry::check()) {
		try
		{
			// Get logged in user
			$user = Sentry::getUser();
		    // Find the user using the user id
		    $user = Sentry::findUserByID($user->id);
		    // Get administrators
		    $admin = Sentry::findGroupByName("Administrators");
		    $subadmin = Sentry::findGroupByName("Sub Administrators");
		    // Check if user is in admin/sub-admin group
		    if(!$user->inGroup($admin) && !$user->inGroup($subadmin)){
		    	// Set system message
				return App::abort(403, "Access denied!!!");
		    }

		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
		    // Set system message
			Session::flash('system_message', ["level"=>"danger", "access"=>"wcp", "type"=>"login", "message"=>"Please Login to continue!"]);
			// Redirect to login page
			return Redirect::to('admin/login');
		}
	}
});


Route::filter('auth.wcp.in', function()
{
	if (Sentry::check())
	{
		// Redirect to /
		return Redirect::to('admin');
	}
});



Route::filter('auth.api', function()
{
	$token = Request::header('token', null);
	$_token = Input::get("_token", null);
	if (Session::token() != $token && Session::token() != $_token)
	{
		if (Request::ajax())
		{
			return Response::json(['status' => 'error', 'level'=>'danger', 'message'=> "Access Denied"], 200);
		}
		else
		{
			return App::abort(404);
		}
	}
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});
