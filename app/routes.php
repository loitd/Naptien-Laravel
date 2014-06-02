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
/*
Route::get('/', function()
{
	return View::make('hello');
});
*/

// My redirection

Route::get('/', function(){
	return Redirect::to('/naptien');
});

//

Route::group(array('before'=>'csrf'), function(){
	//route for resource
	Route::get('/naptien',  array("as"=>"naptien/index", "uses"=>"Naptien@index")); //as => name of route
	Route::post('/naptien', array("as"=>"naptien/store", "uses"=>"Naptien@store"));
	
	Route::get('/napgame',  array("as"=>"napgame/index", "uses"=>"Napgame@index")); //as => name of route
	Route::post('/napgame', array("as"=>"napgame/store", "uses"=>"Napgame@store"));
	
	Route::resource('banking', 	'Banking', array('only'=>array('index', 'store')));
	Route::resource('bankings',	'BankingS', array('only'=>array('index', 'store'))); //banking for softpins
	
	Route::resource('charge',	'Charge', array('only'=>array('index', 'store'))); //
	//Route::resource('napgame', 	'Napgame');
	Route::resource('softpin', 	'Softpin', array('only'=>array('index', 'store', 'show')));
	
	Route::resource('huongdan',	'Huongdan', array('only'=>array('index')));
	Route::resource('tintuc',	'Tintuc', array('only'=>array('index')));

	Route::get('/thanhvien',  array("as"=>"thanhvien/index", "uses"=>"Thanhvien@index")); //as => name of route
	Route::post('/thanhvien', array("as"=>"thanhvien/store", "uses"=>"Thanhvien@store"));
	Route::get('/thanhvien/register',  array("as"=>"thanhvien/register", "uses"=>"Thanhvien@register")); 
	Route::post('/thanhvien/register', array("as"=>"thanhvien/doreg", "uses"=>"Thanhvien@doreg"));
	Route::get('/thanhvien/activate/{code}', array("as"=>"thanhvien/activate", "uses"=>"Thanhvien@activate"));
	Route::get('/thanhvien/profile',  array("as"=>"thanhvien/profile", "uses"=>"Thanhvien@viewprofile")); 
	Route::post('/thanhvien/profile',  array("as"=>"thanhvien/updateprofile", "uses"=>"Thanhvien@updateprofile")); 
	Route::get('/thanhvien/logout', array("as"=>"thanhvien/logout", "uses"=>"Thanhvien@logout"));
	
	Route::get('/thanhvien/remind',  array("as"=>"thanhvien/getremind", "uses"=>"RemindersController@getRemind")); 
	Route::post('/thanhvien/remind',  array("as"=>"thanhvien/postremind", "uses"=>"RemindersController@postRemind")); 
	
	Route::resource('error', 	'Error', array('only'=>array('index')));
});

//Route for captcha
Route::get('/captcha', array("as"=>"captcha",function() { //named as captcha
	$size 	= 5;
	$width = 90;
    $height = 35;
    $xpos	= $width/10;
    $ypos	= $height*3.5/4;
    $font 		= public_path() . '/font/TimesNewRomanBold.ttf';
	$font_size 	= rand(14, 15);
	$font_color	= '#00ee00';
	$randomString = '';
	$max_rotation	= 25;
	//Make rand string		
	$chars = '0123456789abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	 
	for ($i = 0; $i < $size; $i++) 
	{
	    $randomString .= $chars[rand(0, strlen($chars)-1)];
	}	
	Session::put('captcha', $randomString);

	//random angle
	$angle	= rand(-5, $max_rotation);

	//make a line

    
	//return $img->response();
	return Image::canvas($width, $height, '#ffffff')->text($randomString, 
		$xpos, 
		$ypos, 
		$font_size, 
		$font_color, 
		$angle, 
		$font)
	->line('#00aa00', 0, 0, rand(10,$width), rand(0,$height))
	->line('#00aa00', 0, $width, rand(10,$width), rand(0,$height))
	->contrast(rand(20, 80))
	->response();
	//->save('./foo.jpg');
}));

//Route for test ------------------
Route::any('/test', function(){
	//Auth::logout();
	//$user = User::where('email','=','loi_tran_duc@yahoo.com')->first();
	//Auth::login($user);
	//echo Auth::user()->email;
});

