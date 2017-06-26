<?php
namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Role extends Model
{
	use SoftDeletes;
	protected $table = 'backend_roles';
	protected $fillable = ['name', 'display_name', 'description', 'weight'];
    protected $dates = ['deleted_at'];

    public function permissions()
    {
    	return $this->belongsToMany('App\Models\Backend\Permission', 'backend_permission_role', 'role_id', 'permission_id');
    }

    public static function list ()
    {
    	return Cache::remember('BACKEND_CACHE_PERMISSION_ROUTES', 0, function () {
    		$list = Role::orderBy('weight', 'desc')->orderBy('id', 'asc')->get();
    		$formatList = [];
    		foreach ($list as $key => $item) {

    			$formatList[] = [
    				'id' => $item->id,
    				'name' => $item->name,
    				'display_name' => $item->display_name,
    				'description' => $item->description,
    				'weight' => $item->weight,
    				'permissions' => $item->permissions()->pluck('id')
    			];
    		}

    		return $formatList;
    	});
    }
}