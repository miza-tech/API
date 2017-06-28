<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController;
use App\Models\Backend\Role;
use Illuminate\Support\Facades\DB;

class RoleController extends ApiController
{
	public function list (Request $request)
	{
		$roles = Role::list();
		return $this->SUCCESS($roles);
	}

	public function create (Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required|unique:backend_roles,name',
			'backend_id' => 'required|exists:backend_users,id',
			'display_name' => 'required',
			'permissions' => 'array',
			'weight' => 'integer',
		]);
		if ($validator->fails())
		{
			return $this->VALIDATOR_FAIL($validator->errors());
		}

		// DB::beginTransaction();
		$inputData = collect($request->all())->only(collect($rules)->keys()->toArray())->toArray();
		$role = Role::create($inputData);

		if ($role) {
			if (!empty($request->input('permissions'))) {
				$role->permissions()->sync($request->input('permissions'));
			}
			$role->permissions = $role->permissions()->get();
			return $this->SUCCESS($role);
		} else {
			return $this->RESPONSE('FAIL');
		}
	}

	public function update (Request $request, $id)
	{
		$role = Role::find($id);
		if (!$role) {
			return $this->NOT_FOUND();
		}

		$validator = Validator::make($request->all(), [
			'name' => 'required|unique:backend_roles,name,' . $role->id,
			'display_name' => '',
			'backend_id' => 'required|exists:backend_users,id',
			'permissions' => 'array',
			'weight' => 'integer',
		]);
		if ($validator->fails())
		{
			return $this->VALIDATOR_FAIL($validator->errors());
		}

		$updateData = collect($request->all())->only(collect($rules)->keys()->toArray())->toArray();
		$role->update($updateData);
		$role->save();

		if (!empty($request->input('permissions'))) {
			$role->permissions()->sync($request->input('permissions'));
			$role->permissions = $role->permissions()->get();
		}

		return $this->SUCCESS($role);
	}

	public function delete (Request $request, $id)
	{
		$role = Role::find($id);
		if (!$role) {
			return $this->NOT_FOUND();
		}

		$role->delete();

		return $this->SUCCESS();
	}
}