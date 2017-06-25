<?php

namespace App\Http\Controllers;

use Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Backend\Permission;

class ApiController extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	// protected function formatValidationErrors(Validator $validator)
	// {
	// 	return $validator->errors();
	// }

	public function SUCCESS ($data = null, $meta = null)
	{
		return $this->RESPONSE('SUCCESS', [
			'data' => $data,
			'meta' => $meta,
		]);
	}

	public function VALIDATOR_FAIL ($errors)
	{
		return $this->RESPONSE('VALIDATOR_FAIL', [
			'errors' => $errors
		]);
	}

	public function AUTH_FAIL ()
	{
		return $this->RESPONSE('LOGIN_FAIL');
	}

	public function NOT_FOUND ()
	{
		return $this->RESPONSE('NOT_FOUND');
	}

	public function BAD_REQUEST ($errors = null)
	{
		return $this->RESPONSE('BAD_REQUEST', ['errors' => $errors]);
	}

	public function RESPONSE ($stateCode, $data = [])
	{
		$status = config('status.' . $stateCode);
		$response = [
			'code' => $stateCode,
			'msg' => $status['msg']
		];
		$response = array_merge($response, $data);
		return response($response, $status['code']);
	}

	public function validator ($request)
	{
		$permission = Permission::where('name', Route::currentRouteName())->first();
		$rules = json_decode($permission->rules, true);
		$meta = json_decode($permission->meta, true);
		$fields = [];
		$ret = ['pass' => false];

		if (!is_array($rules)) {
			$ret['response'] = $this->BAD_REQUEST('请填写rules');
			return $ret;
		}
		if (!is_array($meta) || !$meta['table']) {
			$ret['response'] = $this->BAD_REQUEST('请填写完整meta信息');
			return $ret;
		}

		foreach ($rules as $field => $rule) {
			$fields[] = $field;
		}

		$inputs = $request->only($fields);
		$validator = Validator::make($inputs, $rules);
		if ($validator->fails()) {
			$ret['response'] = $this->VALIDATOR_FAIL($validator->errors());
			return $ret;
		}

		return ['pass' => true, 'meta' => $meta, 'inputs' => $inputs];
	}
}
