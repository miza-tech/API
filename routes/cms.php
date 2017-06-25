<?php
use App\Models\Cms\Permission;

// user auth
Route::post('auth/password', 	['uses' => 'AuthController@authByPassword']);
Route::post('auth/sms', 		['uses' => 'AuthController@authBySms']);
Route::post('auth/logout', 		['uses' => 'AuthController@logout']);

// app config
Route::get('config',			['uses' => 'UserController@config']);

// user profile
Route::get('profile',			['uses' => 'UserController@profileInfo']);
Route::patch('profile',			['uses' => 'UserController@profileUpdate'])->middleware('auth:cms');

// captcha
Route::get('captcha/img', 		['uses' => 'CaptchaController@img']);
Route::post('captcha/sms', 		['uses' => 'CaptchaController@sms']);


$routes = Permission::routes();
foreach ($routes as $route) {

	$explode = explode('.', $route->name);
	$method = $explode[0];
	$url = $explode[1];
	$conf = [
		'as' => $route->name,
		'uses' => $route->action,
		'middleware' => ['auth:cms']
	];

	if ($route->middleware) {
		$middleware = is_array($route->middleware) ? $route->middleware : [$route->middleware];
		$conf['middleware'] = array_merge($conf['middleware'], $middleware);
	}

	Route::$method($url, $conf);
}