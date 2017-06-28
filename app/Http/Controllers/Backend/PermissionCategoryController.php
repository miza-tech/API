<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController;
use App\Models\Backend\PermissionCategory;
use Illuminate\Support\Facades\DB;

class PermissionCategoryController extends ApiController
{
	public function list (Request $request)
	{
		$list = PermissionCategory::orderBy('id', 'desc')->get();
		return $this->SUCCESS($list);
	}

	public function create (Request $request)
	{
		$validator = Validator::make($request->all(), [
			'display_name' => 'required|unique:backend_permission_categories,display_name',
		]);
		if ($validator->fails())
		{
			return $this->VALIDATOR_FAIL($validator->errors());
		}

		$category = PermissionCategory::create(['display_name' => $request->input('display_name')]);

		if ($category) {
			return $this->SUCCESS($category);
		} else {
			return $this->RESPONSE('FAIL');
		}
	}

	public function update (Request $request, $id)
	{
		$category = PermissionCategory::find($id);
		if (!$category) {
			return $this->NOT_FOUND();
		}

		$validator = Validator::make($request->all(), [
			'display_name' => 'required|unique:backend_permission_categories,display_name,' . $category->id,
		]);
		if ($validator->fails())
		{
			return $this->VALIDATOR_FAIL($validator->errors());
		}

		$category->update(['display_name' => $request->input('display_name')]);

		return $this->SUCCESS($category);
	}

	public function delete (Request $request, $id)
	{
		$category = PermissionCategory::find($id);
		if (!$category) {
			return $this->NOT_FOUND();
		}

		$category->delete();

		return $this->SUCCESS();
	}
}