<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController;
use App\Models\Cms\Department;

class DepartmentController extends ApiController
{
	public function list (Request $request)
	{
		return $this->SUCCESS(Department::tree());
	}

	public function create (Request $request)
	{
		$rules = [
			'display_name' => 'required',
		];
		if ($request->input('parent_id')) {
			$rules['parent_id'] = 'exists:cms_departments,id';
		}
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails())
		{
			return $this->VALIDATOR_FAIL($validator->errors());
		}

		// DB::beginTransaction();
		$inputData = collect($request->all())->only([
			'display_name',
			'parent_id',
			'description',
		])->toArray();

		$department = Department::create($inputData);

		if ($department) {
			Department::fixTree();
			return $this->SUCCESS($department);
		} else {
			return $this->RESPONSE('FAIL');
		}
	}

	public function update (Request $request, $id)
	{
		$department = Department::find($id);
		if (!$department) {
			return $this->NOT_FOUND();
		}

		$rules = [
			'display_name' => 'required',
		];
		if ($request->input('parent_id')) {
			$rules['parent_id'] = 'exists:cms_departments,id';
		}

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails())
		{
			return $this->VALIDATOR_FAIL($validator->errors());
		}

		$updateData = collect($request->all())->only([
			'display_name',
			'parent_id',
			'description'
		])->toArray();

		$department->update($updateData);

		switch ($request->input('move')) {
			case 'up':
				$department->up();
				break;
			case 'down':
				$department->down();
				break;
		}

		Department::fixTree();
		return $this->SUCCESS($department);
	}

	public function delete (Request $request, $id)
	{
		$department = Department::find($id);
		if (!$department) {
			return $this->NOT_FOUND();
		}

		$department->delete();
		Department::fixTree();

		return $this->SUCCESS();
	}
}