<?php
namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Support\Facades\Cache;

class Department extends Model
{
	use NodeTrait;
	protected $table = 'backend_departments';
	protected $fillable = ['display_name', '_lft', '_rgt', 'parent_id', 'description'];
    protected $hidden = ['deleted_at', 'created_at', 'updated_at', '_lft', '_rgt'];

    public static function tree()
    {
    	return Cache::remember('BACKEND_CACHE_DEPARTMENT_TREE', 0, function () {
    		$tree = Department::defaultOrder()->get()->toTree();

            return $tree;
    	});
    }
}