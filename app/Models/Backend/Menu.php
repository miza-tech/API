<?php
namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Support\Facades\Cache;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
	// use SoftDeletes;
	use NodeTrait;
	protected $table = 'backend_menus';
	protected $fillable = ['display_name', '_lft', '_rgt', 'parent_id', 'url', 'description', 'icon', 'route', 'hidden'];
	protected $hidden = ['deleted_at', 'created_at', 'updated_at', '_lft', '_rgt'];

	public function cpermissions()
	{
		return $this->hasMany('App\Models\Backend\Permission', 'menu_id');
	}

	public function permissions()
	{
		return $this->belongsToMany('App\Models\Backend\Permission', 'backend_permission_menu', 'menu_id', 'permission_id');
	}

	public static function tree()
	{
		return Cache::remember('BACKEND_CACHE_MENUS_TREE', 0, function () {
			$tree = Menu::defaultOrder()->get()->toTree();
			Menu::formatTree($tree);

			return $tree;
		});
	}

	public static function list ()
	{
		return Cache::remember('BACKEND_CACHE_MENUS_LIST', 0, function () {
			$tree = Menu::get()->toFlatTree();

			return $tree;
		});
	}

	private static function formatTree ($tree)
	{
		foreach ($tree as $key => $data) {
			$data->permissions = $data->permissions()->pluck('id');
			Menu::formatTree($data->children);
		}
	}
}