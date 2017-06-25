<?php
namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Support\Facades\Cache;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
	// use SoftDeletes;
	use NodeTrait;
	protected $table = 'cms_menus';
	protected $fillable = ['display_name', '_lft', '_rgt', 'parent_id', 'url', 'description', 'icon', 'route'];
    protected $hidden = ['deleted_at', 'created_at', 'updated_at', '_lft', '_rgt'];

	public function cpermissions()
    {
    	return $this->hasMany('App\Models\Cms\Permission', 'menu_id');
    }

    public function permissions()
    {
    	return $this->belongsToMany('App\Models\Cms\Permission', 'cms_permission_menu', 'menu_id', 'permission_id');
    }

    public static function tree()
    {
    	return Cache::remember('CMS_CACHE_MENUS_TREE', 0, function () {
    		$tree = Menu::defaultOrder()->get()->toTree();
            Menu::formatTree($tree);

            return $tree;
    	});
    }

    public static function list ()
    {
        return Cache::remember('CMS_CACHE_MENUS_LIST', 0, function () {
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