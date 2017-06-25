<?php
return [
	'SUCCESS' 				=> ['msg' => '操作成功',			'code' => 200],
	'FAIL' 					=> ['msg' => '操作失败',			'code' => 400],
	'VALIDATOR_FAIL' 		=> ['msg' => '验证失败',			'code' => 400],
	'BAD_REQUEST' 			=> ['msg' => '请求错误',			'code' => 400],
	'LOGIN_FAIL' 			=> ['msg' => '登陆失败',			'code' => 401],
	'AUTH_FORBIDDEN' 		=> ['msg' => '请登录',			'code' => 401],
	'AUTH_INVALID' 			=> ['msg' => '认证错误',			'code' => 401],
	'PERMISSION_FORBIDDEN' 	=> ['msg' => '无权限',			'code' => 401],
	'NOT_FOUND'				=> ['msg' => '请求不存在',		'code' => 404],
	'USER_NOT_FOUND'		=> ['msg' => '用户不存在',		'code' => 404],
	'MAINTAIN'				=> ['msg' => '系统维护中',		'code' => 502],
];