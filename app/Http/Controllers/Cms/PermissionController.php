<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController;
use App\Models\Cms\Permission;
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
		$validator = Validator::make($request->all(), [
			'name' => 'required|unique:cms_permissions,name',
			'display_name' => 'required',
			'action' => 'required',
			// 'middleware' => 'array',
			'weight' => 'integer',
			'category_id' => 'required|exists:cms_permission_categories,id'
		]);
		if ($validator->fails())
		{
			return $this->VALIDATOR_FAIL($validator->errors());
		}

		// DB::beginTransaction();
		$inputData = collect($request->all())->only([
			'name',
			'display_name',
			'action',
			'middleware',
			'category_id',
			'description',
			'weight'
		])->toArray();
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
			'name' => 'unique:cms_permissions,name,'.$permission->id,
			// 'middleware' => 'array',
			'menu_id' => 'exists:cms_menus,id'
		]);
		if ($validator->fails())
		{
			return $this->VALIDATOR_FAIL($validator->errors());
		}

		$updateData = collect($request->all())->only([
			'name',
			'display_name',
			'action',
			'middleware',
			'menu_id',
			'description',
			'weight',
			'category_id'
		])->toArray();

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