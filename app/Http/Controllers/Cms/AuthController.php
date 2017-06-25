<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;
use App\CmsUser;

class AuthController extends ApiController
{
	public function authByPassword (Request $request)
	{
		$credentials = $request->only('phone','password');
		$validator = Validator::make($credentials, [
			'phone' => 'required',
			'password' => 'required|min:6|max:32',
		]);

		if ($validator->fails())
		{
			return $this->VALIDATOR_FAIL($validator->errors());
		}

		$rememberMe = ($request->input('remember_me') == 1);

		if(Auth::guard('cms')->attempt($credentials, $rememberMe)) {
			return $this->SUCCESS(Auth::guard('cms')->user());
		} else {
			return $this->RESPONSE('LOGIN_FAIL');
		}
	}

	public function authBySms (Request $request)
	{

	}

	public function logout ()
	{
		Auth::guard('cms')->logout();
		return $this->SUCCESS();
	}
}