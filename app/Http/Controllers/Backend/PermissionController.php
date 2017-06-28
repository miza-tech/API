<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController;
use App\Models\Backend\Permission;
use Illuminate\Support\Facades\DB;

class PermissionController extends ApiController
{
	public function list (Request $request)
	{
		$roles = Permission::list();

		return $this->SUCCESS($roles);
	}

	public function create (Request $request)
	{
		$rules = [
			'name' => 'required|unique:backend_permissions,name',
			'route' => 'required|unique:backend_permissions,route',
			'display_name' => 'required',
			'action' => 'required',
			'middleware' => '',
			'weight' => 'integer',
			'category_id' => 'required|exists:backend_permission_categories,id',
			'description' => ''
		];
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails())
		{
			return $this->VALIDATOR_FAIL($validator->errors());
		}

		// DB::beginTransaction();
		$inputData = collect($request->all())->only(collect($rules)->keys()->toArray())->toArray();
		$permission = Permission::create($inputData);

		if ($permission) {
			return $this->SUCCESS($permission);
		} else {
			return $this->RESPONSE('FAIL');
		}
	}

	public function update (Request $request, $id)
	{
		$permission = Permission::find($id);
		if (!$permission) {
			return $this->NOT_FOUND();
		}

		$validator = Validator::make($request->all(), [
			'name' => 'required|unique:backend_permissions,name,' . $permission->id,
			'route' => 'required|unique:backend_permissions,route,' . $permission->id,
			'display_name' => 'required',
			'action' => 'required',
			'middleware' => '',
			'weight' => 'integer',
			'category_id' => 'required|exists:backend_permission_categories,id',
			'description' => ''
		]);
		if ($validator->fails())
		{
			return $this->VALIDATOR_FAIL($validator->errors());
		}

		$updateData = collect($request->all())->only(collect($rules)->keys()->toArray())->toArray();
		$permission->update($updateData);
		$permission->save();

		return $this->SUCCESS($permission);
	}

	public function delete (Request $request, $id)
	{
		$permission = Permission::find($id);
		if (!$permission) {
			return $this->NOT_FOUND();
		}

		$permission->delete();

		return $this->SUCCESS();
	}
}