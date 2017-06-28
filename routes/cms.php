<?php
use App\Models\Cms\Permission;

// user auth
Route::post('auth/password', 	['uses' => 'Cms\AuthController@authByPassword']);
Route::post('auth/sms', 		['uses' => 'Cms\AuthController@authBySms']);
Route::post('auth/logout', 		['uses' => 'Cms\AuthController@logout']);

// app config
Route::get('config',			['uses' => 'Cms\UserController@config']);

// user profile
Route::get('profile',			['uses' => 'Cms\UserController@profileInfo']);
Route::patch('profile',			['uses' => 'Cms\UserController@profileUpdate'])->middleware('auth:cms');

// captcha
Route::get('captcha/img', 		['uses' => 'Cms\CaptchaController@img']);
Route::post('captcha/sms', 		['uses' => 'Cms\CaptchaController@sms']);


$routes = Permission::routes();
foreach ($routes as $route) {

	$explode = explode('.', $route->route);
	$method = $explode[0];
	$url = $explode[1];
	$conf = [
		'as' => $route->route,
		'uses' => $route->action,
		'middleware' => ['auth:cms']
	];

	if ($route->middleware) {
		$middleware = is_array($route->middleware) ? $route->middleware : [$route->middleware];
		$conf['middleware'] = array_merge($conf['middleware'], $middleware);
	}

	Route::$method($url, $conf);
}