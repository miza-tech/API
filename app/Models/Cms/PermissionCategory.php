<?php
namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;

class PermissionCategory extends Model
{
	protected $table = 'cms_permission_categories';
	protected $fillable = ['display_name'];
}