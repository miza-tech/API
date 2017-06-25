<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\CmsUser;
use App\Models\Cms\Department;
use App\Models\Cms\Menu;

class UserController extends ApiController
{
	private function getProfile()
	{
		$user = Auth::guard('cms')->user();
		if ($user) {
			$roles = $user->roles();
			$permissions = $user->permissions($user->roles());
			$menus = $user->menus();
			$user->roles = $roles->pluck('id');
			$user->permissions = $permissions->pluck('id');
			$user->menus = $menus->pluck('id');
		}
		return $user;
	}
	public function config (Request $request)
	{
		// return $this->VALIDATOR_FAIL([]);
		$config = [
			'authenticated' => Auth::guard('cms')->check(),
			'user' => CmsUser::profile(),
			'menus' => Menu::tree()
		];
		return $this->SUCCESS($config);
	}

	public function profileInfo (Request $request)
	{
		return $this->SUCCESS($this->getProfile());
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
		$validator = Validator::make($request->all(), [
			'phone' => 'required|unique:cms_users,phone',
			'password' => 'required|min:6|max:32',
			'realname' => '',
			'idCard' => '',
			'department_id' => 'required|exists:cms_departments,id',
			'age' => 'integer',
			'gender' => 'in:male,female',
			'password_reset_needed' => 'boolean'
		]);
		if ($validator->fails())
		{
			return $this->VALIDATOR_FAIL($validator->errors());
		}

		$userData = collect($request->all())->only([
			'phone',
			'password',
			'realname',
			'idCard',
			'department_id',
			'age',
			'gender'
		])->toArray();
		$userData['password'] = Hash::make($userData['password']);

		$ret = CmsUser::create($userData);
		if ($ret) {
			return $this->SUCCESS($ret);
		} else {
			return $this->RESPONSE('FAIL');
		}
	}

	public function list (Request $request)
	{
		$allowFilterFields = [
			'phone' => 'like',
			'realname' => 'like',
			'idCard'=>'like',
			'department_id' => '=',
			'age' => '=',
			'gender' => '='
		];

		$filterData = collect($request->all())->only(['phone', 'realname', 'idCard', 'department_id', 'age', 'gender']);
		if ($filterData->count() > 0) {
			$ret = null;
			foreach ($filterData as $field => $value) {
				$type = $allowFilterFields[$field];
				$searchValue = ($type == 'like') ? '%'.$value.'%' : $value;
				echo $searchValue;
				if (!$ret) {
					$ret = CmsUser::where($field, $type, $searchValue);
				} else {
					$ret = $ret->where($field, $type, $searchValue);
				}
			}

			return $this->SUCCESS($ret->paginate($request->input('size', 20)));
		} else {
			return $this->SUCCESS(CmsUser::where('phone', 'like', '%6228%')->paginate($request->input('size', 20)));
		}
	}

	public function edit (Request $request, $id)
	{
		$user = CmsUser::find($id);
		if (!$user) {
			return $this->NOT_FOUND();
		}

		$validator = Validator::make($request->all(), [
			'phone' => 'unique:cms_users,phone,' . $user->id,
			'age' => 'integer',
			'gender' => 'in:male,female',
			'department_id' => 'exists:cms_departments,id'
		]);
		if ($validator->fails())
		{
			return $this->VALIDATOR_FAIL($validator->errors());
		}

		$updateData = collect($request->all())->only(['phone', 'realname', 'idCard', 'department_id', 'age', 'gender'])->toArray();
		$user->update($updateData);
		$user->save();

		return $this->SUCCESS($user);
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
			'activated' => 0
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