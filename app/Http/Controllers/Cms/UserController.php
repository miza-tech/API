<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\CmsUser;
use App\Models\Cms\Department;
use App\Models\Cms\Menu;

class UserController extends ApiController
{
	public function config (Request $request)
	{
		$config = [
			'authenticated' => Auth::guard('cms')->check(),
			'user' => CmsUser::profile(),
			// 'menus' => Menu::tree()
		];
		return $this->SUCCESS($config);
	}

	public function profileInfo (Request $request)
	{
		return $this->SUCCESS(CmsUser::profile());
	}

	public function profileUpdate (Request $request)
	{
		$validator = Validator::make($request->all(), [
			'age' => 'integer',
			'gender' => 'in:male,female',
		]);
		if ($validator->fails())
		{
			return $this->VALIDATOR_FAIL($validator->errors());
		}

		$updateData = collect($request->all())->only(['age', 'gender'])->toArray();
		$uid = Auth::guard('cms')->id();
		CmsUser::whereId($uid)->update($updateData);

		return $this->SUCCESS(CmsUser::find($uid));
	}

	public function create (Request $request)
	{
		$rules = [
			'phone' => 'required|unique:cms_users,phone',
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

		$user = CmsUser::create($userData);
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
		$user = CmsUser::find($id);
		if (!$user) {
			return $this->NOT_FOUND();
		}

		$rules = [
			'phone' => 'required|unique:cms_users,phone,' . $user->id,
			'realname' => '',
			'idCard' => '',
			'department_id' => 'required|exists:cms_departments,id',
			'age' => 'integer',
			'gender' => 'in:male,female',
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
			'phone' => 'like',
			'realname' => 'like',
			'idCard'=>'like',
			'department_id' => '=',
			'age' => '=',
			'gender' => '=',
			'status' => '='
		];

		$filterData = collect($request->all())
				->only(collect($allowFilterFields)->keys()->toArray());

		$supers = explode(',', config('miza.super'));
		$users = CmsUser::with('department')->whereNotIn('phone', $supers);
		if ($filterData->count() > 0) {
			foreach ($filterData as $field => $value) {
				$type = $allowFilterFields[$field];
				$searchValue = ($type == 'like') ? '%'.$value.'%' : $value;
				$users->where($field, $type, $searchValue);
			}
		}
		return $this->SUCCESS($users->paginate($request->input('size', config('miza.page_size'))));
	}

	public function resetPassword (Request $request, $id)
	{
		$user = CmsUser::find($id);
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

	public function delete (Request $request, $id)
	{
		$user = CmsUser::find($id);
		if (!$user) {
			return $this->NOT_FOUND();
		}

		$user->delete();

		return $this->SUCCESS();
	}
}