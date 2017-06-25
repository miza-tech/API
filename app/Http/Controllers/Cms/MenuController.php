<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController;
use App\Models\Cms\Menu;

class MenuController extends ApiController
{
	public function list (Request $request)
	{
		return $this->SUCCESS(Menu::tree());
	}

	public function create (Request $request)
	{
		$rules = [
			'display_name' => 'required',
			'url' => '',
			'permissions' => 'array',
		];
		if ($request->input('parent_id')) {
			$rules['parent_id'] = 'exists:cms_menus,id';
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
			'url',
			'route',
			'description',
			'icon'
		])->toArray();

		$menu = Menu::create($inputData);

		if ($menu) {
			if (!empty($request->input('permissions'))) {
				$menu->permissions()->sync($request->input('permissions'));
				$menu->permissions = $menu->permissions()->get();
			}

			Menu::fixTree();
			return $this->SUCCESS($menu);
		} else {
			return $this->RESPONSE('FAIL');
		}
	}

	public function update (Request $request, $id)
	{
		$menu = Menu::find($id);
		if (!$menu) {
			return $this->NOT_FOUND();
		}

		$rules = [
			'display_name' => 'required',
			'url' => '',
			'permissions' => 'array',
		];
		if ($request->input('parent_id')) {
			$rules['parent_id'] = 'exists:cms_menus,id';
		}

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails())
		{
			return $this->VALIDATOR_FAIL($validator->errors());
		}

		$updateData = collect($request->all())->only([
			'display_name',
			'parent_id',
			'url',
			'route',
			'description',
			'icon'
		])->toArray();

		$menu->update($updateData);

		if (!empty($request->input('permissions'))) {
			$menu->permissions()->sync($request->input('permissions'));
			$menu->permissions = $menu->permissions()->get();
		}

		switch ($request->input('move')) {
			case 'up':
				$menu->up();
				break;
			case 'down':
				$menu->down();
				break;
		}

		Menu::fixTree();
		return $this->SUCCESS($menu);
	}

	public function delete (Request $request, $id)
	{
		$menu = Menu::find($id);
		if (!$menu) {
			return $this->NOT_FOUND();
		}

		$menu->delete();
		Menu::fixTree();

		return $this->SUCCESS();
	}
}