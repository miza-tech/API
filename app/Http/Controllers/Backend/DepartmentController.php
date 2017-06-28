<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController;
use App\Models\Backend\Department;

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
			'backend_id' => 'required|exists:backend_accounts,id',
			'parent_id' => '',
			'description' => ''
		];
		if ($request->input('parent_id')) {
			$rules['parent_id'] = 'exists:backend_departments,id';
		}
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails())
		{
			return $this->VALIDATOR_FAIL($validator->errors());
		}

		// DB::beginTransaction();
		$inputData = collect($request->all())->only(collect($rules)->keys()->toArray())->toArray();

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
			'backend_id' => 'required|exists:backend_accounts,id',
			'parent_id' => '',
			'description' => ''
		];
		if ($request->input('parent_id')) {
			$rules['parent_id'] = 'exists:backend_departments,id';
		}

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails())
		{
			return $this->VALIDATOR_FAIL($validator->errors());
		}

		$updateData = collect($request->all())->only(collect($rules)->keys()->toArray())->toArray();
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