<?php
namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Support\Facades\Cache;

class Department extends Model
{
	use NodeTrait;
	protected $table = 'cms_departments';
	protected $fillable = ['display_name', '_lft', '_rgt', 'parent_id', 'description'];
    protected $hidden = ['deleted_at', 'created_at', 'updated_at', '_lft', '_rgt'];

    public static function tree()
    {
    	return Cache::remember('CMS_CACHE_DEPARTMENT_TREE', 0, function () {
    		$tree = Department::defaultOrder()->get()->toTree();

            return $tree;
    	});
    }
}