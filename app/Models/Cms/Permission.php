<?php
namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
// use App\Models\Menu;

class Permission extends Model
{
	protected $table = 'cms_permissions';
    protected $fillable = ['name', 'display_name', 'action', 'middleware', 'category_id', 'description', 'weight'];

    public static function list()
    {
        return Cache::remember('CMS_CACHE_PERMISSION_LIST', 0, function () {
            $permissions = Permission::with('category')->orderBy('weight', 'desc')->orderBy('id', 'desc')->get();
            $formatPermissions = [];

            foreach ($permissions as $item) {
                $item->category = $item->category ? $item->category->display_name : '其它';
                $formatPermissions[] = [
                    'id' => $item->id,
                    'category_id' => $item->category_id,
                    'category_name' => $item->category,
                    'name' => $item->name,
                    'display_name' => $item->display_name,
                    'action' => $item->action,
                    'middleware' => $item->middleware,
                    'description' => $item->description,
                    'weight' => $item->weight,
                ];
            }

            return $formatPermissions;
        });
    }

	public static function routes()
	{
		return Cache::remember('CMS_CACHE_PERMISSION_ROUTES', 0, function () {
    		$lists = [];
    		foreach (Permission::all() as $key => $row) {
    			$lists[$row->name] = $row;
    		}
    		return $lists;
		});
	}

	public function roles()
    {
    	return $this->belongsToMany('App\Models\Cms\Role', 'cms_permission_role', 'permission_id', 'role_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Cms\PermissionCategory', 'category_id');
    }
}