<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;
use App\Models\Backend\Account;
use App\BackendUser;

class AccountController extends ApiController
{
	public function info (Request $request, $id)
	{
		$backend = Account::with('administrator')->find($id);
		if (!$backend) {
			return $this->NOT_FOUND();
		}

		return $this->SUCCESS($backend);
	}

	public function list (Request $request)
	{
		$allowFilterFields = [
			'name' => 'like',
			'super_user_id' => '=',
			'display_name' => 'like',
			'contact_name' => 'like',
			'contact_phone' => 'like'
		];

		$filterData = collect($request->all())->only(collect($allowFilterFields)->keys()->toArray());
		$accounts = Account::with('administrator');
		if ($filterData->count() > 0) {
			foreach ($filterData as $field => $value) {
				$type = $allowFilterFields[$field];
				$searchValue = ($type == 'like') ? '%'.$value.'%' : $value;
				$accounts->where($field, $type, $searchValue);
			}
		}
		return $this->SUCCESS($accounts->paginate($request->input('size', config('miza.page_size'))));
	}

	public function create (Request $request)
	{
		$rules = [
			'name' => 'required|unique:backend_accounts,name',
			'super_username' => 'required|unique:backend_users,username',
			'super_password' => 'required|min:6|max:32',
			'super_password_reset_needed' => 'boolean',
			'status' => 'in:nonactivated,activated,freeze',
			'super_status' => 'required|in:nonactivated,activated,freeze',
			'display_name' => 'required|unique:backend_accounts,display_name',
			'contact_name' => '',
			'contact_phone' => '',
			'description' => '',
		];

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails())
		{
			return $this->VALIDATOR_FAIL($validator->errors());
		}

		// 开启事务
		DB::beginTransaction();
		$success = false;
		// 创建管理员
		$user = BackendUser::create([
			'username' => $request->input('super_username'),
			'password' => Hash::make($request->input('super_password')),
			'password_reset_needed' => $request->input('super_password_reset_needed'),
			'status' => $request->input('super_status'),
			'backend_id' => 0
		]);
		if ($user) {
			// 创建后台账号
			$inputData = collect($request->all())->only(collect($rules)->keys()->toArray())->toArray();
			$inputData['super_user_id'] = $user->id;

			$backend = Account::create($inputData);
			if ($backend) {
				if($user->update(['backend_id' => $backend->id])) {
					$success = true;
				}
			}
		}

		if (!$success) {
			DB::rollBack();
			return $this->RESPONSE('FAIL');
		} else {
			DB::commit();
			return $this->SUCCESS($backend);
		}
	}

	public function update (Request $request, $id)
	{
		$backend = Account::find($id);
		if (!$backend) {
			return $this->NOT_FOUND();
		}

		$rules = [
			'display_name' => 'required|unique:backend_accounts,display_name,' . $backend->id,
			'contact_name' => '',
			'contact_phone' => '',
			'description' => '',
			'status' => 'in:nonactivated,activated,freeze',
			'super_status' => 'required|in:nonactivated,activated,freeze',
		];

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails())
		{
			return $this->VALIDATOR_FAIL($validator->errors());
		}

		$updateData = collect($request->all())->only(collect($rules)->keys()->toArray())->toArray();
		$backend->update($updateData);
		$backend->administrator()->update(['status' => $request->input('super_status')]);

		return $this->SUCCESS($backend);
	}

	public function delete (Request $request, $id)
	{
		$backend = Account::find($id);
		if (!$backend) {
			return $this->NOT_FOUND();
		}

		$backend->delete();
		return $this->SUCCESS();
	}
}