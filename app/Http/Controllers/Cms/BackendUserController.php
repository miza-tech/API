<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;
use App\BackendUser;

class BackendUserController extends ApiController
{
	public function create (Request $request)
	{
		$rules = [
			'backend_id' => 'required|exists:backend_users,id',
			'phone' => 'required|unique:backend_users,phone',
			'username' => 'required|unique:backend_users,username',
			'password' => 'required|min:6|max:32',
			'realname' => '',
			'idCard' => '',
			'department_id' => 'required|exists:cms_departments,id',
			'age' => 'integer',
			'gender' => 'in:male,female',
			'password_reset_needed' => 'boolean',
			'status' => 'in:nonactivated,activated,freeze',
			'roles' => 'array'
		];
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails())
		{
			return $this->VALIDATOR_FAIL($validator->errors());
		}

		$userData = collect($request->all())->only(collect($rules)->keys()->toArray())->toArray();
		$userData['password'] = Hash::make($userData['password']);

		$user = BackendUser::create($userData);
		if ($user) {
			if (!empty($request->input('roles'))) {
				$user->roles()->sync($request->input('roles'));
			}
			return $this->SUCCESS($user);
		} else {
			return $this->RESPONSE('FAIL');
		}
	}

	public function edit (Request $request, $id)
	{
		$user = BackendUser::find($id);
		if (!$user) {
			return $this->NOT_FOUND();
		}

		$rules = [
			'phone' => 'required|unique:backend_users,phone,'.$user->id,
			'username' => 'required|unique:backend_users,username.'.$user->id,
			'password' => 'required|min:6|max:32',
			'realname' => '',
			'idCard' => '',
			'department_id' => 'required|exists:cms_departments,id',
			'age' => 'integer',
			'gender' => 'in:male,female',
			'password_reset_needed' => 'boolean',
			'status' => 'in:nonactivated,activated,freeze',
			'roles' => 'array'
		];

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails())
		{
			return $this->VALIDATOR_FAIL($validator->errors());
		}

		$updateData = collect($request->all())->only(collect($rules)->keys()->toArray())->toArray();
		if ($user->update($updateData)) {
			if (!empty($request->input('roles'))) {
				$user->roles()->sync($request->input('roles'));
			}
			return $this->SUCCESS($user);
		} else {
			return $this->RESPONSE('FAIL');
		}

		return $this->SUCCESS($user);
	}

	public function list (Request $request)
	{
		$allowFilterFields = [
			'backend_id' => '=',
			'phone' => 'like',
			'username' => 'like',
			'realname' => 'like',
			'idCard'=>'like',
			'department_id' => '=',
			'age' => '=',
			'gender' => '=',
			'status' => '='
		];

		$filterData = collect($request->all())
				->only(collect($allowFilterFields)->keys()->toArray());

		$users = BackendUser::with('department')->with('backend');
		if ($filterData->count() > 0) {
			foreach ($filterData as $field => $value) {
				$type = $allowFilterFields[$field];
				$searchValue = ($type == 'like') ? '%'.$value.'%' : $value;
				$users->where($field, $type, $searchValue);
			}
		}
		return $this->SUCCESS($users->paginate($request->input('size', config('miza.page_size'))));
	}

	public function delete (Request $request, $id)
	{
		$user = BackendUser::find($id);
		if (!$user) {
			return $this->NOT_FOUND();
		}

		$user->delete();

		return $this->SUCCESS();
	}

	public function resetPassword (Request $request, $id)
	{
		$user = BackendUser::find($id);
		if (!$user) {
			return $this->NOT_FOUND();
		}

		$validator = Validator::make($request->all(), [
			'password' => 'required|min:6|max:32',
			'password_reset_needed' => 'required|boolean'
		]);
		if ($validator->fails())
		{
			return $this->VALIDATOR_FAIL($validator->errors());
		}
		$user->update([
			'password' => bcrypt($request->input('password')),
			'password_reset_needed' => $request->input('password_reset_needed'),
			'status' => 'nonactivated'
		]);
		$user->save();

		return $this->SUCCESS($user);
	}
}