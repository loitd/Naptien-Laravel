<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/
/*
	Handle General Exceptions will be place first
*/
App::error(function(Exception $exception, $code)
{
	Log::error($exception);
	//return Redirect::to(URL::action('Error@index'))->with('message', 'Lỗi: Đã có lỗi không xác định xảy ra. Vui lòng liên hệ quản trị viên để được hỗ trợ.');
	return View::make('layout.error', array('what'=>'Lỗi: Đã có lỗi không xác định xảy ra. Vui lòng liên hệ quản trị viên để được hỗ trợ.'));
});

/*
	Handle Illuminate\Database\QueryException by Loitd
*/
App::error(function(Illuminate\Database\QueryException $exception)
{
    // Handle the exception...
	return View::make('layout.error', array('what'=>'Lỗi: Trang web bạn tìm kiếm không tồn tại. Vui lòng liên hệ quản trị viên để được hỗ trợ.'));
});

/*
	Handle Symfony\Component\HttpKernel\Exception\NotFoundHttpException by Loitd
*/
App::error(function(Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception)
{
    // Handle the exception...
	return View::make('layout.error', array('what'=>'Lỗi: Trang web bạn tìm kiếm không tồn tại. Vui lòng liên hệ quản trị viên để được hỗ trợ.'));
});

App::error(function(UnexpectedValueException $exception)
{
    // Handle the exception...
	return View::make('layout.error', array('what'=>'Lỗi: Đã có lỗi không xác định xảy ra. Vui lòng liên hệ quản trị viên để được hỗ trợ.'));
});

App::error(function(Illuminate\Session\TokenMismatchException $exception)
{
    // Handle the exception...
	return View::make('layout.error', array('what'=>'Lỗi: Trang web bạn tìm kiếm không tồn tại. Vui lòng liên hệ quản trị viên để được hỗ trợ.'));
});

//
//Next, you need to register your custom Validator extension: with validator resolver
Validator::resolver(function($translator, $data, $rules, $messages){
	return new LoiValidator($translator, $data, $rules, $messages);
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';

/*
	Add constant to the framework by Loitd
*/
require app_path().'/config/constants.php';
